<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Specification;
use App\Models\SubCategory;
use App\Models\Upload;
use App\Models\User;
use App\Models\ViewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::query()->select('uuid', 'name')->get();
//        return Product::with('sizes')->get();
        return view('admin.products.index', compact('categories'));
    }

    public function store(Request $request)
    {

        $rules = [
            'images' => 'required',
            'images.*' => 'required|mimes:jpeg,jpg,png|max:2048',
            'price' => 'required|int',
            'fsize' => 'required',
            'fsize.*' => 'string',
            'fquantity' => 'required',
            'fquantity.*' => 'string',
            'category_uuid' => 'required|exists:categories,uuid',
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:45';
            $rules['details_' . $key] = 'required|string';

        }

        $request->validate($rules);
//        $request->merge([
//            'number' => '3424324'
//        ]);
        $data = $request->only('number', 'name', 'price', 'details', 'category_uuid', 'address');
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
            $data['details'][$key] = $request->get('name_' . $key);

        }
        $product = Product::query()->create($data);
//dd(1);
        for ($i = 0; $i < count($request->fsize); $i++) {
            ProductSize::query()->create([
                'quantity' => $request->fquantity[$i],
                'size' => $request->fsize[$i],
                'product_uuid' => $product->uuid
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->images as $item) {
                UploadImage($item, Product::PATH_IMAGE, Product::class, $product->uuid, false, null, Upload::IMAGE); // one يعني انو هذه الصورة تابعة لمعرض الاعمال الي من نوع الفيديوهات

            }
        }
        return response()->json([
            'item_added'
        ]);
    }

    public function update(Request $request)
    {

        $rules = [
            'images' => 'nullable',
            'images.*' => 'nullable|mimes:jpeg,jpg,png|max:2048',
            'price' => 'required|int',
            'fsize' => 'required',
            'fsize.*' => 'string',
            'fquantity' => 'required',
            'fquantity.*' => 'string',
            'category_uuid' => 'required|exists:categories,uuid',
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:45';
            $rules['details_' . $key] = 'required|string|max:45';

        }


        $request->validate($rules);
        $product = Product::findOrFail($request->uuid);
        $data=$request->only('number','name','price','details','category_uuid');
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
            $data['details'][$key] = $request->get('details_' . $key);
        }
        $product->update($data);
        $product->sizes()->delete();

        if (isset($request->delete_images)) {
            $images = Upload::query()->where('imageable_type', Product::class)->where('imageable_id', $product->uuid)->whereNotIn('uuid', $request->delete_images)->get();

            foreach ($images as $item) {
                File::delete(public_path(Product::PATH_IMAGE . $item->filename));
                $item->delete();
            }
        }
        for ($i = 0; $i < count($request->fsize); $i++) {
            ProductSize::query()->create([
                'quantity' => $request->fquantity[$i],
                'size' => $request->fsize[$i],
                'product_uuid' => $product->uuid
            ]);
        }
        if ($request->hasFile('images')) {
            foreach ($request->images as $item) {
                UploadImage($item, Product::PATH_IMAGE, Product::class, $product->uuid, false, null, Upload::IMAGE);
            }
        }
        return response()->json([
            'item_edited'
        ]);
    }

    public function destroy($uuid)
    {

        try {
            $uuids = explode(',', $uuid);
            $product = Product::query()->withoutGlobalScope('status')->whereIn('uuid', $uuids)->get();

            foreach ($product as $item) {
                foreach ($item->imageProduct as $image) {
                    Storage::delete('public/' . @$image->path);
                    $image->delete();
                }
                $item->sizes()->delete();
                $item->delete();
            }
            return response()->json([
                'item_deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'err'=>$e
            ]);
        }
    }

    public function indexTable(Request $request)
    {
        $product = Product::query()->withoutGlobalScope('status')->orderByDesc('created_at');
        return Datatables::of($product)
            ->filter(function ($query) use ($request) {
                if ($request->status) {
                    ($request->status == 1) ? $query->where('status', $request->status) : $query->where('status', 0);
                }
                if ($request->price) {
                    $query->where('price', $request->price);
                }
                if ($request->number) {
                    $query->where('number', $request->number);
                }
                if ($request->get('name')) {
                    $locale = app()->getLocale();
                    $query->where('name->'.locale(), 'like', "%{$request->get('name')}%");
                }

                if ($request->category_uuid) {
                    $query->where('category_uuid', $request->category_uuid);
                }

//
            })
            ->addColumn('checkbox', function ($que) {
                return $que->uuid;
            })
            ->addColumn('action', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $que->uuid . '" ';
                $data_attr .= 'data-price="' . $que->price . '" ';
                $data_attr .= 'data-number="' . $que->number . '" ';
                $data_attr .= 'data-category_uuid="' . $que->category_uuid . '" ';
                $data_attr .= 'data-images_uuid="' . implode(',', $que->imageProduct->pluck('uuid')->toArray()) . '" ';
                $data_attr .= 'data-images="' . implode(',', $que->imageProduct->pluck('path')->toArray()) . '" ';
                $data_attr .= 'data-size="' . implode(',', $que->sizes->pluck('size')->toArray()) . '" ';
                $data_attr .= 'data-quantity="' . implode(',', $que->sizes->pluck('quantity')->toArray()) . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-name_' . $key . '="' . $que->getTranslation('name', $key) . '" ';
                    $data_attr .= 'data-details_' . $key . '="' . $que->getTranslation('details', $key) . '" ';

                }

                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';

                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger btn_delete" data-uuid="' . $que->uuid .
                    '">' . __('delete') . '</button>';

                return $string;
            })->addColumn('status', function ($que) {
                $currentUrl = url('/');
                if ($que->status == 1) {
                    $data = '
<button type="button"  data-url="' . $currentUrl . "/admin/products/updateStatus/0/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-success " data-uuid="' . $que->uuid .
                        '">' . __('active') . '</button>
                    ';
                } else {
                    $data = '
<button type="button"  data-url="' . $currentUrl . "/admin/products/updateStatus/1/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-danger " data-uuid="' . $que->uuid .
                        '">' . __('inactive') . '</button>
                    ';
                }
                return $data;
            })
            ->rawColumns(['action', 'status'])->toJson();
    }

    public function updateStatus($status, $sub)
    {
        $uuids = explode(',', $sub);

        $product = Product::query()->withoutGlobalScope('status')
            ->whereIn('uuid', $uuids)
            ->update([
                'status' => $status
            ]);
        return response()->json([
            'item_edited'
        ]);
    }


}

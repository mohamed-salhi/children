<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function index()
    {

        return view('admin.categories.index');
    }


    public function store(Request $request)
    {
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:45';
        }
        $rules['image'] = 'required|image';

        $request->validate($rules);

        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        $category = Category::create($data);
        if ($request->has('image')) {
            UploadImage($request->image, Category::PATH_IMAGE, Category::class, $category->uuid, true, null, Upload::IMAGE);
        }
        return response()->json([
            'item_added'
        ]);
    }

    public function update(Request $request)
    {


        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $request->validate($rules);

        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        $category = Category::query()->withoutGlobalScope('status')->findOrFail($request->uuid);
        $category->update($data);
        if ($request->has('image')) {
            UploadImage($request->image, Category::PATH_IMAGE, Category::class, $category->uuid, true, null, Upload::IMAGE);
        }
        //        $category->types()->sync($request->types);
        return response()->json([
            'item_edited'
        ]);
    }

    public function destroy($uuid)
    {

        try {
            $uuids = explode(',', $uuid);
            $Category =  Category::whereIn('uuid', $uuids)->get();

            foreach ($Category as $item) {
                Storage::delete('public/' . @$item->imageCategory->path);

                //                File::delete(public_path(Category::PATH_IMAGE.$item->imageCategory->filename));
                $item->imageCategory()->delete();
                $item->delete();
            }
            return response()->json([
                'item_deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'err'
            ]);
        }
    }

    public function indexTable(Request $request)
    {
        $category = Category::query()->withoutGlobalScope('status')->orderByDesc('created_at');

        return Datatables::of($category)
            ->filter(function ($query) use ($request) {
                if ($request->get('name')) {
                    $query->where('name->' . locale(), 'like', "%{$request->name}%");

                    foreach (locales() as $key => $value) {
                        if ($key != locale())
                            $query->orWhere('name->' . $key, 'like', "%{$request->name}%");
                    }
                }
                if ($request->status) {
                    ($request->status == 1) ? $query->where('status', $request->status) : $query->where('status', 0);
                }
            })
            ->addColumn('checkbox', function ($que) {
                return $que->uuid;
            })
            ->addColumn('action', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $que->uuid . '" ';
                $data_attr .= 'data-image="' . $que->image . '" ';

                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-name_' . $key . '="' . $que->getTranslation('name', $key) . '" ';
                }
                $string = '';
                //                if ($user->can('competitions-edit')){
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                //                }
                //                if ($user->can('competitions-delete')){
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger btn_delete" data-uuid="' . $que->uuid .
                    '">' . __('delete') . '</button>';
                //                }
                return $string;
            })->addColumn('status', function ($que) {
                $currentUrl = url('/');
                if ($que->status == 1) {
                    $data = '
<button type="button"  data-url="' . $currentUrl . "/admin/categories/updateStatus/0/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-success " data-uuid="' . $que->uuid .
                        '">' . __('active') . '</button>
                    ';
                } else {
                    $data = '
<button type="button"  data-url="' . $currentUrl . "/admin/categories/updateStatus/1/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-danger " data-uuid="' . $que->uuid .
                        '">' . __('inactive') . '</button>
                    ';
                }
                return $data;
            })
            //            ->addColumn('sub-category', function ($que) {
            //                $currentUrl = url('/');
            //                return '   <a class="btn btn-gradient-success " href="'.route('categories.sub',$que->uuid).'" type="button"                                                                                         ><span><i
            //                                                    class="fa fa-plus"></i>'.__('show').'</span>
            //                                        </button>';
            //            })
            ->rawColumns(['action', 'status'])->toJson();
    }

    public function UpdateStatus($status, $sub)
    {
        $uuids = explode(',', $sub);

        $activate =  Category::query()->withoutGlobalScope('status')
            ->whereIn('uuid', $uuids)
            ->update([
                'status' => $status
            ]);
        return response()->json([
            'item_edited'
        ]);
    }
}

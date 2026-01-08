<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function index()
    {

        return view('admin.blog.category.index');
    }

    public function store(Request $request)
    {
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:45';
        }
        $request->validate($rules);
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        if (empty($data['slug']) || !is_array($data['slug'])) {
            $data['slug'] = generateLocalizedSlugs($data['name']);
        }
        $data['created_by'] = auth('admin')->id();
        $BlogCategory = BlogCategory::create($data);
        return response()->json(['item_edited']);
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
        $BlogCategory = BlogCategory::query()->withoutGlobalScope('status')->findOrFail($request->id);
        if (empty($data['slug']) || !is_array($data['slug'])) {
            $data['slug'] = generateLocalizedSlugs($data['name']);
        }

        $data['updated_by'] = auth('admin')->id();
        $BlogCategory->update($data);
        return response()->json(['item_edited']);
    }


    public function indexTable(Request $request)
    {
        $category = BlogCategory::query()->withoutGlobalScope('status')->orderByDesc('created_at');

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
                return $que->id;
            })
            ->addColumn('action', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-id="' . $que->id . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-name_' . $key . '="' . $que->getTranslation('name', $key) . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger btn_delete" data-uuid="' . $que->id .
                    '">' . __('delete') . '</button>';
                return $string;
            })->addColumn('status', function ($que) {
                $currentUrl = url('/');
                if ($que->status == 1) {
                    $data = '<button type="button"  data-url="' . $currentUrl . "/admin/blog/category/updateStatus/0/" . $que->id . '" id="btn_update" class=" btn btn-sm btn-outline-success " data-uuid="' . $que->id .
                        '">' . __('active') . '</button>';
                } else {
                    $data = '<button type="button"  data-url="' . $currentUrl . "/admin/blog/category/updateStatus/1/" . $que->id . '" id="btn_update" class=" btn btn-sm btn-outline-danger " data-uuid="' . $que->id .
                        '">' . __('inactive') . '</button>';
                }
                return $data;
            })
            ->rawColumns(['action', 'status'])->toJson();
    }

     public function UpdateStatus($status, $sub)
    {

        $uuids = explode(',', $sub);
        $activate =  BlogCategory::query()->withoutGlobalScope('status')
            ->whereIn('id', $uuids)
            ->update([
                'status' => $status
            ]);
        return response()->json([
            'item_edited'
        ]);
    }
    public function destroy($id)
    {
        try {
            $ids = array_filter(explode(',', $id));

            $deleted = BlogCategory::withoutGlobalScope('status')
                ->whereIn('id', $ids)
                ->delete();

            if ($deleted === 0) {
                return response()->json(['err' => true], 404);
            }

            return response()->json(['item_deleted' => true]);
        } catch (\Throwable $e) {
            return response()->json(['err' => true], 500);
        }
    }


}

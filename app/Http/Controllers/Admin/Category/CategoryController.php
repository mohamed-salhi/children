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

<<<<<<< HEAD
      $request->validate($rules);
=======
        $request->validate($rules);
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea

        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
<<<<<<< HEAD
        $category= Category::create($data);
=======
        $category = Category::create($data);
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
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
<<<<<<< HEAD
      $request->validate($rules);
=======
        $request->validate($rules);
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea

        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        $category = Category::query()->withoutGlobalScope('status')->findOrFail($request->uuid);
        $category->update($data);
        if ($request->has('image')) {
            UploadImage($request->image, Category::PATH_IMAGE, Category::class, $category->uuid, true, null, Upload::IMAGE);
        }
<<<<<<< HEAD
//        $category->types()->sync($request->types);
        return response()->json([
            'item_edited'
        ]);

=======
        //        $category->types()->sync($request->types);
        return response()->json([
            'item_edited'
        ]);
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
    }

    public function destroy($uuid)
    {

        try {
<<<<<<< HEAD
            $uuids=explode(',', $uuid);
            $Category=  Category::whereIn('uuid', $uuids)->get();

            foreach ($Category as $item){
                Storage::delete('public/' . @$item->imageCategory->path);

//                File::delete(public_path(Category::PATH_IMAGE.$item->imageCategory->filename));
=======
            $uuids = explode(',', $uuid);
            $Category =  Category::whereIn('uuid', $uuids)->get();

            foreach ($Category as $item) {
                Storage::delete('public/' . @$item->imageCategory->path);

                //                File::delete(public_path(Category::PATH_IMAGE.$item->imageCategory->filename));
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
                $item->imageCategory()->delete();
                $item->delete();
            }
            return response()->json([
                'item_deleted'
            ]);
<<<<<<< HEAD
        }catch (\Exception $e){
=======
        } catch (\Exception $e) {
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
            return response()->json([
                'err'
            ]);
        }
    }

    public function indexTable(Request $request)
    {
<<<<<<< HEAD
        $category= Category::query()->withoutGlobalScope('status')->orderByDesc('created_at');
=======
        $category = Category::query()->withoutGlobalScope('status')->orderByDesc('created_at');
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea

        return Datatables::of($category)
            ->filter(function ($query) use ($request) {
                if ($request->get('name')) {
                    $query->where('name->' . locale(), 'like', "%{$request->name}%");

                    foreach (locales() as $key => $value) {
                        if ($key != locale())
                            $query->orWhere('name->' . $key, 'like', "%{$request->name}%");
                    }
                }
<<<<<<< HEAD
                if ($request->status){
                    ($request->status==1)?$query->where('status',$request->status):$query->where('status',0);
                }

            })
            ->addColumn('checkbox',function ($que){
=======
                if ($request->status) {
                    ($request->status == 1) ? $query->where('status', $request->status) : $query->where('status', 0);
                }
            })
            ->addColumn('checkbox', function ($que) {
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
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
<<<<<<< HEAD
//                if ($user->can('competitions-edit')){
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
//                }
//                if ($user->can('competitions-delete')){
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger btn_delete" data-uuid="' . $que->uuid .
                    '">' . __('delete') . '</button>';
//                }
                return $string;
            }) ->addColumn('status', function ($que)
            {
                $currentUrl = url('/');
                if ($que->status==1){
                    $data='
<button type="button"  data-url="' . $currentUrl . "/admin/categories/updateStatus/0/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-success " data-uuid="' . $que->uuid .
                        '">' . __('active') . '</button>
                    ';
                }else{
                    $data='
=======
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
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
<button type="button"  data-url="' . $currentUrl . "/admin/categories/updateStatus/1/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-danger " data-uuid="' . $que->uuid .
                        '">' . __('inactive') . '</button>
                    ';
                }
                return $data;
            })
<<<<<<< HEAD
//            ->addColumn('sub-category', function ($que) {
//                $currentUrl = url('/');
//                return '   <a class="btn btn-gradient-success " href="'.route('categories.sub',$que->uuid).'" type="button"                                                                                         ><span><i
//                                                    class="fa fa-plus"></i>'.__('show').'</span>
//                                        </button>';
//            })
            ->rawColumns(['action', 'status'])->toJson();
    }

    public function UpdateStatus($status,$sub)
    {
        $uuids=explode(',', $sub);

        $activate =  Category::query()->withoutGlobalScope('status')
            ->whereIn('uuid',$uuids)
            ->update([
                'status'=>$status
=======
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
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
            ]);
        return response()->json([
            'item_edited'
        ]);
    }
<<<<<<< HEAD




=======
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
}

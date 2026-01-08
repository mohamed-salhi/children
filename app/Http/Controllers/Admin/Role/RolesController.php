<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{

    public static function middleware(): array
    {
        return [
            // examples with aliases, pipe-separated names, guards, etc:
            new Middleware('permission:admin'),
        ];
    }
    public function index(Request $request)
    {

        $permissions = Permission::all();
        return view('admin.roles.index',compact('permissions'));
//            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


//    public function create()
//    {
//        $permission = Permission::get();
//        return view('admin.roles.create',compact('permission'));
//    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name'), 'guard_name' => 'admin']);
        $role->syncPermissions($request->input('permissions'));

        return 'done';
    }

//    public function show($id)
//    {
//        $role = Role::find($id);
//        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
//            ->where("role_has_permissions.role_id",$id)
//            ->get();
//
//        return view('roles.show',compact('role','rolePermissions'));
//    }
//
//
//    public function edit($id)
//    {
//        $role = Role::find($id);
//        $permission = Permission::get();
//        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
//            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
//            ->all();
//
//        return view('admin.roles.edit',compact('role','permission','rolePermissions'));
//    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::find($request->id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return 'done';
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
            ->with('success','Role deleted successfully');
    }
    public function indexTable(Request $request)
    {


        $items = Role::query()->orderBy('created_at');
        return Datatables::of($items)
//            ->addColumn('checkbox', function ($que) {
//                return $que->id;
//            })
            ->addColumn('action', function ($que) {
                $data_attr = 'data-id="' . $que->id . '" ';
                $data_attr .= 'data-name="' . $que->name . '" ';

                $data_attr .= 'data-permissions="' . implode(',', $que->permissions->pluck('name')->toArray()) . '," ';

                $string = '';
                $route = url('/admin/managers/edit/' . $que->id);
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger btn_delete" data-uuid="' . $que->id .
                    '">' . __('delete') . '</button>';


                return $string;
            })

            ->rawColumns([ 'action'])
            ->make(true);
    }
}

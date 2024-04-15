<?php

namespace App\Http\Controllers\API\Admin;

use Validator;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Spatie\Permission\Models\Role;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Input: searchRole
     * Output: Result search name role
     */
    public function searchRole(Request $request)
    {
        if($request->searchRole){
            $search = $request->searchRole;
            $collection = collect(Role::all());
            $filtered = $collection->where('name', $search);
            $filtered->all();
            return response()->json([
                'filtered' => $filtered
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No data'
            ]);
        }
    }
    public function index(Request $request)
    {
       
        // $roles = Role::orderBy('id','DESC')->get();
        // $permission = Permission::get();

        // return response()->json([
        //     'roles' => $roles,
        //     'permission' => $permission,
        //     'status' => true

        // ]);


        $roles=Role::orderBy('id','DESC')->where('parent_role',0)->get();
        $permissions=Permission::all()->groupBy(function($permission){
            return explode('.',$permission->slug)[0];
        });
        //return $permissions;
       
        return response()->json([
            'status'=>true,
            'roles'=>$roles,
            'permissions'=>$permissions
        ]);
        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $permission = Permission::get();
        // return response()->json([
        //     'permission' => $permission,
        // ]);

      

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //** both create roles and create permissions  */
        // $validator = Validator::make($request->all(),[
        //     'name' => 'required|unique:roles,name',
        //     'permission' => 'required',
        // ]);
        // $role = Role::create(['name' => $request->name]);
        // $role->syncPermissions($request->permission);
        // //$request->permissionName tạo quyền mới
        // $permission = Permission::create(['name' => $request->permissionName]);
        // return response()->json([
        //     'status' => true,
        //     'role' => $role,
        //     'permission' => $permission
        // ]);
        try{
            $data = $request->all();
            $validator = Validator::make($request->all(),[
                'name' => 'required',
               
            ]);
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }

            $idAdmin = Auth::guard('admin')->user()->adminid;
       
            $AdminDepartment = Admin::with('roles')->find($idAdmin);
            //return   $AdminDepartment->roles;
            $rolePa=null;
            foreach( $AdminDepartment->roles as $item){
                if($item->parent_role==0){
                    $rolePa=$item->id;
                }
                
            }
            $role=new Role();
            $role->name=$data['name'];
           
            $role->guard_name="web";
            $role->parent_role=$rolePa;
            //return $data['permissionId'];
            $role->save();
           
            $role->permissions()->attach($data['permissionId']);
            //$role->save();
            return response()->json([
                'roles'=>$role,
                'status'=>true
                
            ]);
        }catch(\Throwable $th){
            return response()->json([
              'status' => false,
              'message' => $th->getMessage()
            ]);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $role = Role::find($id);
        // $rolePermissions = Permission::join("role_has_permissions",
        //                                     "role_has_permissions.permission_id","=","permissions.id")
        //                                ->where("role_has_permissions.role_id",$id)
        //                                ->get();
        // return response()->json([
        //     'role' => $role,
        //     'rolePermissions' => $rolePermissions
        // ]);

        $role=Role::where('id',$id)->first();
        $permissions=Permission::all()->groupBy(function($permission){
         return explode('.',$permission->slug)[0];
     });
 
         //$permission=$role->permissions;
        return response()->json([
             'status'=>true,
             'role'=>$role,
             'permissions'=>$permissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $role = Role::find($id);
        // $permission = Permission::get();
        // $rolePermission = DB::table("role_has_permissions")
        //                     ->where("role_has_permissions.role_id",$id)
        //                     ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        //                     ->all();
        // return response()->json([
        //     'role' => $role,
        //     'permission' => $permission,
        //     'rolePermission' => $rolePermission,
        //     'status' => true
        // ]);
       $role=Role::where('id',$id)->first();
       $permissions=Permission::all()->groupBy(function($permission){
        return explode('.',$permission->slug)[0];
    });

        //$permission=$role->permissions;
       return response()->json([
            'status'=>true,
            'role'=>$role,
            'permissions'=>$permissions
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(),[
        //     'name' =>'required',
        //     'permission' => 'required',
        //     'permission.*' => 'min:1',
        // ]);
        // $role = Role::find($id);
        // $role->name = $request->name;
        // $role->save();
        // $role->syncPermissions($request->permission);
        // return response()->json([
        //     'status' => true
        // ]);
        try{
            $data = $request->all();
            $validator = Validator::make($request->all(),[
                'name' => 'required',
               
            ]);
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $role=Role::find($id);
            $role->name=$data['name'];
           
            $role->guard_name="web";
            //return $data['permissionId'];
            $role->save();
           
            $role->permissions()->sync($request->input('permissionId', []));
            //$role->save();
            return response()->json([
                'roles'=>$role,
                'status'=>true
                
            ]);
        }catch(\Throwable $th){
            return response()->json([
              'status' => false,
              'message' => $th->getMessage()
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::where("id", $id)->delete();
        return response()->json([
            'status' => true
        ]);
    }
}

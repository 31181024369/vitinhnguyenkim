<?php

namespace App\Http\Controllers\Api\Admin;

use Hash;
use Validator;
use App\Models\Log;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Arr;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use App\Models\RoleHasPermission;
use Illuminate\Support\Facades\DB;
// use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Spatie\Permission\Models\Permission;
use App\Models\Member;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Department;

use App\Models\Product;
use App\Models\ProductDesc;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Input:searchRole
     * Output: Result of Role search
     */
    public function searchAccount(Request $request)
    {
        $countRole = Role::count();
        $listRole = Role::all();
        if(isset($request->searchRole)){
            $search = $request->searchRole;
            $collection = collect(Role::all());
            $filtered = $collection->where('name', $search);
            $filtered->all();
            return response()->json([
                'filtered' => $filtered,
                
            ]);
        }else{
            $collection = collect(Role::all());
            return response()->json([
                'collection' => $collection,
                'countRole' => $countRole,
            ]);
        }
    }
    public function searchAccountFilter(Request $request)
    {
        $countRole = Role::count();
        $listRole = Role::all();
        $toDate = $request->input('toDate') ? $request->input('toDate') : '';
        $orderCode = $request->input('orderCode') ? $request->input('orderCode') : '';

        
            return response()->json([
                'countRole' => $countRole,
                'listRole' => $listRole
            ]);
        
    }


     public function index(Request $request)
     {
        $idAdmin = Auth::guard('admin')->user();
        $AdminSuper = Admin::where('status',0)->first();
        $Department=Department::orderBy('id',"asc")->get();
        $depart=$request['depart'];
       
        $adminList=Admin::with('permissions','department');
        if($request->data == 'undefined' || $request->data =="")
        {
             if($idAdmin-> status == 1 && $idAdmin-> depart_id !=1)
             {
                $adminList->where('depart_id',$idAdmin-> depart_id);
             }
        }
        else{
            $adminList->where("username", 'like', '%' . $request->data . '%');
            if($idAdmin-> status == 1 && $idAdmin-> depart_id !=1)
            {
                $adminList->where("username", 'like', '%' . $request->data . '%')
                ->where('depart_id',$idAdmin-> depart_id);
            }
        }
        if(isset($depart)){
            $adminList->where('depart_id',$depart);
        }
        $listAdmin= $adminList->paginate(10);
        if($idAdmin-> status == 2 && $idAdmin-> depart_id ==2)
        {
            $listAdmin = "";
        }
         return response()->json([
            'listUser'=>$listAdmin,
            'adminSuper'=> $AdminSuper
        ]);
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         $roles = Role::pluck('name','name')->all();
         return response()->json($roles);
     }
    
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
         try{
             $validator = Validator::make($request->all(),[
                 'username' => 'required',
                 'password' => 'required',
             ]);
             if($validator->fails()){
                 return response()->json([
                     'message'=>'Validations fails',
                     'errors'=>$validator->errors()
                 ],422);
             }

             $check = Admin::where('username',$request->username)->first();
             if($check != '')
             {
                return response()->json([
                    'message'=>'username',
                    'status'=>'false'
                ],202);
             }
             $permissions=Permission::all();
             $idAdmin = Auth::guard('admin')->user()->adminid;

             $userAdmin = new Admin();
             $userAdmin -> username = $request['username'];
             $userAdmin -> password = Hash::make($request['password']);
             $userAdmin -> email = $request['email'];
             $userAdmin -> level = 1;
             $userAdmin -> display_name = $request['name'];
             $userAdmin -> avatar = isset($request['avatar']) ? $darequestta['avatar'] : null;
             $userAdmin -> skin = "";
             $userAdmin -> is_default = 0;
             $userAdmin -> lastlogin = 0;
             $userAdmin -> code_reset = Hash::make($request['password']);
             $userAdmin -> menu_order = 0;
             $userAdmin -> phone = $request['phone'];
             $userAdmin ->parentAdmin = Auth::guard('admin')->user()->adminid;
             $userAdmin -> status = $request['status'];
             $userAdmin -> depart_id= $request['depart_id'];
             $userAdmin -> leader= $request['leader_id'];
             $userAdmin -> status_20= $request['status_20'];
             $userAdmin -> save();
             $userAdmin->permissions()->attach($request['permissionId']);

             return response()->json([
                 'status' => true,
                 'userAdmin' => $userAdmin,
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
     public function show()
     {
        $id = Auth::guard('admin')->user()->adminid;
         $userAdmin = Admin::with('permissions','department')->where('adminid',$id)->first();
         return response()->json([
            'status'=>true,
            'data'=> $userAdmin
         ]);

     }
 
     public function edit($id)
     {
        $userAdminDetail = Admin::with('permissions','department')->find($id);
        $perId=[];
        foreach($userAdminDetail->permissions as $item){
            $perId[]=$item->id;
        }
         return response()->json([
             'userAdminDetail' => $userAdminDetail,
             'PerId'=>$perId
         ]);
     }
     public function leaderDepartment(Request $request,$id){
        try{
            $leader=Admin::where('depart_id',$id)->where('status',1)->get();
            return response()->json([
                'status' => true,
                'leader' => $leader,
                
            ]);
        }catch(\Throwable $th){
            return response()->json([
              'status' => false,
              'message' => $th->getMessage()
            ]);
        }
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
        try{
            $userAdmin = Admin::with('permissions','department')->find($id);
            $userAdmin -> password = !empty($request['password'])?Hash::make($request['password']):$userAdmin -> password;
            $userAdmin -> email = $request['email']?$request['email']:$userAdmin -> email;
            $userAdmin -> display_name = $request['name']?$request['name']: $userAdmin -> display_name ;
            $userAdmin -> phone = $request['phone']?$request['phone']:$userAdmin -> phone;
            $userAdmin -> status = $request['status']?$request['status']:$userAdmin -> status;
            $userAdmin ->parentAdmin = $request['parentAdmin']?$request['parentAdmin']:$userAdmin ->parentAdmin;
            $userAdmin -> depart_id= $request['depart_id']?$request['depart_id']:$userAdmin -> depart_id;
            $userAdmin -> leader= $request['leader_id']?$request['leader_id']:$userAdmin -> leader_id;
            $userAdmin -> status_20= $request['status_20']?$request['status_20']:$userAdmin -> status_20;
            $userAdmin -> save();
            if(isset($request['permissionId']))
            {
                $userAdmin->permissions()->sync($request->input('permissionId', []));
            }
            return response()->json([
                'status' => true,
                'userAdmin' => $userAdmin,
                
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
        $adminid= Auth::guard('admin')->user()->adminid;
        $userAdmin = Admin::where('adminid', $adminid)->first();
        if($userAdmin->status==2){
            Admin::where("adminid", $id)->delete();
            return response()->json([
                'status' => true
            ]);

        }else{
            return response()->json([
                'status' => true,
                'message'=>'bạn không thể xóa admin supper'
            ]);
        }
     }
     public function changePassword(Request $request,User $user)
     {
        $user = User::find(auth('api')->user());
        return response()->json([
            'user' => $user,
        ]);
     }
     public function password(Request $request, User $user) 
    {
        $this->Validate($request,[
            'password' => 'required',
            'newPassword' => 'required|string|min:6|max:255|different:password',
            'confirmPassword' => 'required|same:new_password',
        ]);
        $user = request()->user();
            if(!Hash::check($request->password, $user->password)){
                return response()->json([
                    'status' => false,
                ]);
            }
        $user->password = Hash::make($request->newPassword);
        $user->save();
        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }
    public function log(Request $request)
    {
        $adminLog=DB::table('adminlogs')
        ->join('admin', 'admin.adminid', '=', 'adminlogs.adminid')
        ->select('adminlogs.*', 'admin.username','admin.display_name');
        if ($request->input('page') !== null && $request->input('page') !== 'all') {
            $adminLog->where('cat',$request->input('page'));
        }
        if($request->input('admin') !== null && $request->input('admin') !== '0'){
            $adminLog->where('username',$request->input('admin'));
        }
        if($request->input('data') !== null){
            $adminLog->where('username','like','%' .$request->input('data'). '%');
        }
       $adminLogs=$adminLog->orderBy('time', 'desc')->paginate(10);
        return response()->json([
            'status'=>true,
            'listLog'=>$adminLogs
        ]);
    }
    
}

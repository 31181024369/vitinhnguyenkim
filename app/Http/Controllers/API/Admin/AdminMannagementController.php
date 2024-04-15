<?php

namespace App\Http\Controllers\API\Admin;

use Hash;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\AdminGroup;
use App\Models\Permission;
use App\Models\Role;

class AdminMannagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function checkPermission($permission){
        if(!Auth::guard('admin')->user()->hasPermission($permission)){
            return response()->json([
                'message' => "bạn không có quyền vào phòng ban",
                'status'=>true
                ]
            );
        }

    }
    public function index(Request $request)
    {
        $idAdmin = Auth::guard('admin')->user()->adminid;
       
        $AdminDepartment = Admin::find($idAdmin);
      
        $adminMember=Admin::where('level',$request->level)->get();
        $adminManage=Admin::with('roles')->where('level',$request->level)->where('status',1)->first();
        $number=null;
        foreach($adminManage->roles as $item){
            $number=$item->id;
        }
        return response()->json([
            'adminMember' => $adminMember,
            '$adminManage'=> $adminManage,
            'status'=>true
            ]
        );


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            if(!Auth::guard('admin')->user()->hasPermission('department.view')){
                return response()->json([
                    'message' => "bạn không có quyền vào phòng ban",
                    'status'=>true
                    ]
                );
            }

            $idAdmin = Auth::guard('admin')->user()->adminid;
       
            $AdminDepartment = Admin::find($idAdmin);

            if($AdminDepartment->status==1){

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
                $userAdmin = new Admin();
                $userAdmin -> username = $request['username'];
                $userAdmin -> password = Hash::make($request['password']);
                $userAdmin -> email = $request['email'];
                $userAdmin -> level =  $AdminDepartment->level;
                $userAdmin -> display_name = $request['display_name'];
                $userAdmin -> avatar = isset($request['avatar']) ? $request['avatar'] : null;
                $userAdmin -> skin = "";
                $userAdmin -> is_default = 0;
                $userAdmin -> lastlogin = 0;
                $userAdmin -> code_reset = Hash::make($request['password']);
                $userAdmin -> menu_order = 0;
                $userAdmin -> phone = $request['phone'];
                $userAdmin -> save();
                $userAdmin->roles()->attach($request['roleId']);
                return response()->json([
                    'status' => true,
                    'userAdmin' => $userAdmin,
                ]);
            }else{
                return response()->json([
                    'status' => true,
                    'message' => 'Bạn không có quyền thêm thành viên',
                    
                ]);
            }

        }
        catch(\Throwable $th){
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
        //
        $adminMember=Admin::where('adminid',$id)->first();
        return response()->json([
            'admin' => $adminMember,
            'status'=>true
            ]
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $adminMember=Admin::where('adminid',$id)->first();
        return response()->json([
            'admin' => $adminMember,
            'status'=>true
            ]
        );
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
            if(!Auth::guard('admin')->user()->hasPermission('department.update')){
                return response()->json([
                    'message' => "bạn không có quyền vào phòng ban",
                    'status'=>true
                    ]
                );
            }
            $idAdmin = Auth::guard('admin')->user()->adminid;
            $AdminDepartment = Admin::find($idAdmin);
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
            $userAdmin = Admin::where('adminid',$id)->where('level', $AdminDepartment->level)->first();
            $userAdmin -> username = $request['username'];
            $userAdmin -> password = Hash::make($request['password']);
            $userAdmin -> email = $request['email'];
            $userAdmin -> level = $AdminDepartment->level;
            $userAdmin -> display_name = $request['display_name'];
            $userAdmin -> avatar = isset($request['avatar']) ? $request['avatar'] : null;
            $userAdmin -> skin = "";
            $userAdmin -> is_default = 0;
            $userAdmin -> lastlogin = 0;
            $userAdmin -> code_reset = Hash::make($request['password']);
            $userAdmin -> menu_order = 0;
            $userAdmin -> phone = $request['phone'];
            $userAdmin -> save();
            $userAdmin->roles()->sync($request['roleId']);
            return response()->json([
                'status' => true,
                'userAdmin' => $userAdmin,
            ]);
         
        }
        catch(\Throwable $th){
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
        if(!Auth::guard('admin')->user()->hasPermission('department.view')){
            return response()->json([
                'message' => "bạn không có quyền vào phòng ban",
                'status'=>true
                ]
            );
        }
       
        $idAdmin = Auth::guard('admin')->user()->adminid;
       
        $AdminDepartment = Admin::find($idAdmin);

        if($AdminDepartment->status==0)
        {

            $admin=Admin::where('adminid',$id)->first();

            if($admin->status!=1){
                $adminMember=Admin::where('adminid',$id)->delete();
                return response()->json([
                'status'=>true
                ]);
            }
            else{
                return response()->json([
                    'status'=>true,
                    'message'=>'Bạn không được xóa trưởng bộ phận'
                ]);

            }

           
        }else
        {
            return response()->json([
                'status' => true,
                'message' => 'Bạn không có quyền xóa thành viên',
                
            ]);
        }
    }
}

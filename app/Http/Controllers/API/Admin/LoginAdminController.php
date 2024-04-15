<?php

namespace App\Http\Controllers\API\Admin;

use Validator;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class LoginAdminController extends Controller
{
    public function login (Request $request)
     {
        $val = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        if ($val->fails()) {
            return response()->json($val->errors(), 202);
        }
        
        $admin = Admin::where('username',$request->username)->first();
        if(isset($admin)!=1)
        {
            return response()->json([
                'status' => false,
                'mess' => 'username'
            ]);
        }
        
         $check =  $admin->makeVisible('password');
        
        if(Hash::check($request->password,$check->password)){
                $success=  $admin->createToken('Admin')->accessToken;
                return response()->json([
                    'status' => true,
                    'token' => $success,
                    //'admin' => $admin
                ]);
        }else {
            return response()->json([
                    'status' => false,
                    'mess' => 'pass'
            ]);
        }
        
    }
}

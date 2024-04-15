<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Admin;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\API\Admin\AbstractController;
use App\Http\Controllers\Controller;
use App\Exports\MemberExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class MemberSupportController extends Controller
{
    public function index(Request $request)
    {
    
        try {
            $idAdmin = Auth::guard('admin')->user()->adminid;
            $admin=Admin::where('adminid',$idAdmin)->first();
            if($admin->depart_id==1){
                $list = admin::where('depart_id',2)->get();

            }else if($admin->depart_id==2){
                $list = admin::where('depart_id',2)->where('leader',$idAdmin)->get();

            }
            
            
            return response()->json([
                'status'=>true,
                'list'=>$list
            ]);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];

            return response()->json($response, 500);
        }
    }
}
<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceDesc;

class ServiceController extends Controller
{
    public function index(){
        $service = Service::with('serviceDesc')->orderBy('service_id','desc')->paginate(6);
        return response()->json([
            "status"=>true,
            "services"=>$service
        ]);
    }
    public function detail(Request $request, $slug){
        $listServiceDesc = ServiceDesc::with('service')->where('friendly_url', $slug)->first();
        return response()->json([
          'status'=> true,
          'service' => $listServiceDesc
      ]);
    }
}

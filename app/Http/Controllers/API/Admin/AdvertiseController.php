<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Adpos;
use App\Models\Advertise;
use Illuminate\Http\Request;
use App\Rules\AdvertiseValidate;
use App\Http\Controllers\API\AbstractController;
use App\Http\Controllers\Controller;

class AdvertiseController extends Controller
{
    

    protected function getModel()
    {
        return new Advertise();
    }

    public function index(Request $request)
    {
        
        try {

            $pos=$request['pos'];
            $module_show=$request['module_show'];
            $query=Advertise::orderBy('id','desc');
            if(empty($request->input('data'))||$request->input('data')=='undefined' ||$request->input('data')=='')
            {
                $list = $query;
            }
            else{
                $list = $query->where("title", 'like', '%' . $request->input('data') . '%');
            }
            if(isset($pos)){
                 $list = $query->where("pos",$pos);
            }
            if(isset($module_show)){
                $list = $query->where("module_show",$module_show);
            }
            $listAdvertise=$list->paginate(10);

            
            $response = [
                'status' => 'success',
                'list' => $listAdvertise
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];

            return response()->json($response, 500);
        }
    }
    public function destroy($id)
    {
        $list = Advertise::Find($id)->delete();
    }
    public function edit($id)
    {
        $list = Advertise::find($id);
          return response()->json([
            'status'=> true,
            'list' => $list
        ]);
    }
}
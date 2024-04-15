<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Adpos;
use App\Models\Advertise;
use Illuminate\Http\Request;
use App\Rules\AdposValidate;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class AdposController extends Controller
{
    

    protected function getModel()
    {
        return new Adpos();
    }
    

    
    public function index(Request $request)
    {
        try {
            if($request->data == 'undefined' || $request->data =="")
            {
                $list = Adpos::get();
            }
            else{
                $list = Adpos::where("title", 'like', '%' . $request->data . '%')->get();
            }
            $response = [
                'status' => 'success',
                'list' => $list 
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
    
    public function store(Request $request)
    {
        $adpos = new Adpos();
        try {
            $validator = AdposValidate::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $adpos->fill([
                
                'name' => $request->input('name'),
                'title' => $request->input('title'),
                'width' => $request->input('width'),
                'height' => $request->input('height'),
                'n_show' => $request->input('show'),
                'description' => $request->input('description'),
                'display' => $request->input('display'),
                'menu_order' => 0
            ])->save();
            $response = [
                'status' => 'success',
                'adpos' => $adpos
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
        $list = Adpos::Find($id)->delete();
        return $list;
        $list = Adpos::Find($id)->delete();
        return response()->json([
            'status'=> true,
        ]);
    }
    public function edit($id)
    {
        
        $list = Adpos::find($id);
          return response()->json([
            'status'=> true,
            'list' => $list
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = AdposValidate::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
        $adpos = new Adpos();
        $listAdpos = Adpos::Find($id);
        $listAdpos->fill([
            'name' => $request->input('name'),
            'cat_id' =>$request->input('cat_id'),
            'title' => $request->input('title'),
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'n_show' => $request->input('show'),
            'description' => $request->input('description'),
            'display'  => $request->input('display'),
            'menu_order' => 1
        ])->save();   
    }
}
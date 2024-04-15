<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\ListCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\AbstractController;

class ListCartController extends AbstractController
{
    protected function getModel()
    {
        return new ListCart();
    }
    public function index()
    {
        try {
            $list = parent::index();
            $listCart = ListCart::with('member','productDesc')->get();
            foreach($listCart as $list) {
                $data[] = [
                    'id' => $list->id,
                    'memberName' => isset($list->member->username ) ? $list->member->username : 'No Name',
                    'productName' => isset($list->productDesc->title) ? $list->productDesc->title : 'No Product',
                    'quality' => $list->quality,
                    'price' => $list->quality,
                    'title' => $list->title,
                    'status' => $list->status,
                ];
            }
            return response()->json([
                'listCart' => $listCart,
                'status' => true
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
    public function create()
    {
        try {
            $list = parent::create();

            $response = [
                'status' => 'success',
                'list' => $list 
            ];
            return response()->json([
                'list' => $list,
                'status' => true
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
    
    public function store(Request $request)
    {
        try {
            $list = parent::store($request);
            $response = [
                'status' => 'success',
                'list' => $list 
            ];
            return response()->json([
                'list' => $list,
                'status' => true
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

    public function show(Request $request,$id)
    {
    }
    
    public function edit($id)
    {
       
    }
    
    public function update(Request $request, $id)
    {
        
    }
    
    public function destroy($id)
    {
        
      
    }
}

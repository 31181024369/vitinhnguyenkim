<?php

namespace App\Http\Controllers\API\Chat;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Controllers\API\Chat\AbstractController;
use App\Events\Client\Chat;
use Pusher\Pusher;
use App\Models\Conversation;

class ChatUserController extends AbstractController
{

    public function getModel()
    {
        return new Member();
    }

    public function index()
    {
       
        try {
            $list = parent::index();

            $response = [
                'status' => 'success',
                'list' => $list 
            ];
           
            // return response()->json($response, 200);
            return view("chat.user");
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
            return response()->json($response, 200);
        }  catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];

            // return response()->json($response, 500);
            return view("chat.user");
        }
    }
    
    public function store(Request $request)
    {
        try {
            
         
        } catch (\Exception $e) {
            
        }
    }

    

    public function show(Request $request, $id)
    {
        $list = Product::where('id', $id)->get();
        return response()->json($list, 200);
    }
    
    public function edit($id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        return view('chat.user', compact('product'));
    }
    
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        $product->update($request->all());
        return redirect()->route('chat.user.index')->with('success', 'Product updated successfully.');
    }
    
    public function destroy($id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        $product->delete();
        return redirect()->route('chat.user.index')->with('success', 'Product deleted successfully.');
    }
}

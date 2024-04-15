<?php

namespace App\Http\Controllers\API\Chat;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Conversation;
use App\Http\Controllers\API\Chat\AbstractController;
use App\Events\Client\Chat;


class ChatAdminController extends AbstractController
{
    protected function getModel()
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
            return view("chat.admin");
            // return response()->json($response, 200);
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
            return response()->json($response, 500);
        }
    }
    
    public function store(Request $request)
    {
        $admin = auth()->user();
        $message = $request->input('message');

        event(new ChatMessage($admin, $message));

        return response()->json(['success' => true]);
    }

    public function show(Request $request, $id)
    {
    }
    
    public function edit($id)
    {
        try {
            $list = parent::edit($id);
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
    
    public function update(Request $request, $id)
    {
        try {
            $list = parent::update($request, $id);
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
    
    public function destroy($id)
    {
        try {
            $list = parent::destroy($id);
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
        $list = parent::destroy($id);
      
    }
}

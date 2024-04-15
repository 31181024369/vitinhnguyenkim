<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HirePost;
use App\Models\HireCategory;
class HirePostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $hirePost=HirePost::orderBy('id','desc')->get();
            return response()->json([
                'status'=>true,
                'data'=>$hirePost
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
    public function showHirePost($hire_cate_id,$name){
        try {
            $hirePost=HirePost::where('hire_cate_id',$hire_cate_id)
            ->where('name',$name)
            ->orderBy('id','desc')->get();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];
            return response()->json($response, 500);
        }
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
        try {
            $hirePost=new HirePost();
            $hirePost->name=$request->name;
            $hirePost->salary=$request->salary;
            $hirePost->status=$request->status;
            $hirePost->address=$request->address;
            $hirePost->experience=$request->experience;
            $hirePost->deadline=$request->deadline;
            $hirePost->information=$request->information;
            $hirePost->rank=$request->rank;
            $hirePost->number=$request->number;

            $hirePost->form=$request->form;
            $hirePost->degree=$request->degree;
            $hirePost->department=$request->department;
            $hirePost->slug=$request->slug;
            $hirePost->meta_keywords=$request->meta_keywords;
            $hirePost->meta_description=$request->meta_description;
            $hirePost->hire_cate_id=$request->hire_cate_id;
            $hirePost->image=$request->image;
           
            $hirePost->save();
            return response()->json([
                'status'=>true,
                'mess'=>'success create hireCategory'
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $hirePost=HirePost::where('id',$id)->first();
            return response()->json([
                'status'=>true,
                'data'=>$hirePost
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $hirePost=HirePost::where('id',$id)->first();
            $hirePost->name=$request->name;
            $hirePost->salary=$request->salary;
            $hirePost->status=$request->status;
            $hirePost->address=$request->address;
            $hirePost->experience=$request->experience;
            $hirePost->deadline=$request->deadline;
            $hirePost->information=$request->information;
            $hirePost->rank=$request->rank;
            $hirePost->number=$request->number;

            $hirePost->form=$request->form;
            $hirePost->degree=$request->degree;
            $hirePost->department=$request->department;
            $hirePost->slug=$request->slug;
            $hirePost->meta_keywords=$request->meta_keywords;
            $hirePost->meta_description=$request->meta_description;
            $hirePost->hire_cate_id=$request->hire_cate_id;
            $hirePost->image=$request->image;
            $hirePost->save();
            return response()->json([
                'status'=>true,
                'mess'=>'success update hireCategory'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $hirePost=HirePost::where('id',$id)->first();
            if($hirePost) {
                $hirePost->delete();
            }
            return response()->json([
                'status'=>true,
                'data'=>$hirePost
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

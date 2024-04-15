<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HireCategory;
class HireCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $hireCategory=HireCategory::where('id','desc')->get();
            return response()->json([
                'status'=>true,
                'data'=> $hireCategory
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            $hireCategory=new HireCategory();
            $hireCategory->name=$request->name;
            $hireCategory->slug=$request->slug;
            $hireCategory->status=$request->status;
            $hireCategory->save();
            return response()->json([
                'status'=>true,
                'mess'=>'success hireCategory'
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
            $hireCategory=HireCategory::where('id',$id)->first();
            return response()->json([
                'status'=>true,
                'data'=>$hireCategory
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
            $hireCategory=HireCategory::where('id',$id)->first();
            $hireCategory->name=$request->name;
            $hireCategory->slug=$request->slug;
            $hireCategory->status=$request->status;
            $hireCategory->save();
            return response()->json([
                'status'=>true,
                'mess'=>'update HireCategory'
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
            $hireCategory=HireCategory::where('id',$id)->first();
            if($hireCategory) {
                $hireCategory->delete();
            }
            return response()->json([
                'status'=>true,
                'mess'=>'delete HireCategory'
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

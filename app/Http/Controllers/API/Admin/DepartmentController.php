<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminMenu;
use App\Models\AdminGroup;
use App\Models\AdminPermission;
use App\Models\Department;
use App\Models\Permission;

use Validator;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class DepartmentController extends Controller
{
    
    /**
         * Input: name="search", method="GET", route="order-status-search"
         * Output: list of results
         */
    public function search(Request $request)
    {
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $listDepartment = AdminGroup::where('name', 'LIKE', '%'.$search.'%')->get();
            return response()->json($listDepartment);
        }else{
            return response()->json([
                'message' => 'Invalid search parameters  provided for this search term.',
                'status' => true
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
           
        // if(!Auth::guard('admin')->user()->hasPermission('department.view')){
        //     return response()->json([
        //         'message' => "bạn không có quyền vào phòng ban",
        //         'status'=>true
        //         ]
        //     );
        // }
        if(empty($request->input('data'))||$request->input('data')=='undefined' ||$request->input('data')=='')
            {
                $listDepartment = Department::all();
            }
        else{
            $listDepartment = Department::where("name", 'like', '%' . $request->input('data') . '%')->get();
        }
        return response()->json([
            'status' => true,
            'listDepartment' => $listDepartment
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listDepartment = Department::all();
        return response()->json([
            'status' => true,
            'listDepartment' => $listDepartment
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $data = $request->all();
            $validator = Validator::make($request->all(),[
                'name' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $check = Department::where('name',$data['name'])->first();
            if($check != '')
            {
                return response()->json([
                    'message'=>'name',
                    'status'=>'false'
                ],202);
            }
            $derpartment = new Department();
            $derpartment->name = $data['name'];
            $derpartment->save();
           
            return response()->json([
                          'status' => true,
                          'data'=>$derpartment
                        ]);
        }catch(\Throwable $th){
            return response()->json([
              'status' => false,
              'message' => $th->getMessage()
            ]);
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
       
        $derpartmentId = Department::find($id);
        return response()->json([
            'derpartmentId' => $derpartmentId
        ]);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Department::find($id);
        $adminMenu = AdminMenu::get();
        foreach ($adminMenu as $value) {
            $dataMenu[] = [
                'id' => $value->id,
                'title_vi' => $value->title_vi,
                'link' => $value->link,
                'status' => explode(',', $value->status),
                'position' => $value->position,
            ];
        }
       
        return response()->json([
            'derpartmentId' => $data,
            'adminMenu' => $dataMenu,
            'status'=>true
        ]);
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
        
        try{
           //return response()->json($request->all());
            $validator = Validator::make($request->all(),[
                'name' =>'required',
            ]);
            if($validator->fails()){
                return response()->json([
                   'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $data=$request->all();
            $check = Department::where('name',$data['name'])->where('id','!=',$id)->first();
            //return response()->json($check=='');
            if($check != '')
            {
                return response()->json([
                    'message'=>'name',
                    'status'=>'false'
                ],202);
            }

            $derpartment = Department::find($id);
            $derpartment->name = $data['name'];
           

            $derpartment->save();
            return response()->json([
                'status' => true,
            ]);
        }catch(\Throwable $th){
            return response()->json([
             'status' => false,
             'message' => $th->getMessage()
            ]);
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
        $derpartmentId = Department::find($id);
        $derpartmentId->delete();
        return response()->json([
            'status' => true
        ]);
    }
}

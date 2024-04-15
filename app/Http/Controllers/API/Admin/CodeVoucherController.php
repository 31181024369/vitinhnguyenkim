<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\codeVoucherProduct;
use App\Models\infoVoucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
class CodeVoucherController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function paginate($items, $perPage = 5, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage * $perPage) - $perPage ;
        $itemstoshow = array_slice($items , $offset , $perPage);
        
        return new LengthAwarePaginator($itemstoshow ,$total   ,$perPage);
   }
    public function loginVoucher(Request $request){
        try{

            $currentDate = Carbon::now();
            $start = Carbon::createFromDate(null, 4, 14); // Ngày bắt đầu là ngày 14/4
            $end = Carbon::createFromDate(null, 7, 16); // Ngày kết thúc là ngày 16/7

            // if (!$currentDate->between($start, $end)) {
            //     return response()->json([
            //         'status' => false,
            //         'mess'=>'not expired yet'
            //     ]);
                
            // }
           
            $login=codeVoucherProduct::where('code',$request->code)->first();

            if(isset($login)){
                $info=infoVoucher::where('code',$request->code)->first();
                if(isset($info) && $info->count==1){
                    return response()->json([
                        'status' => false,
                        'mess'=>'code used'
                    ]);
                }
                else{
                    $newInfo=new infoVoucher();
                    $newInfo->name=$request->name;
                    $newInfo->phone=$request->phone;
                    $newInfo->code=$request->code;
                    $newInfo->count=1;
                    $newInfo->admin_id=$request->admin_id;
                    $newInfo->mapx=$request->mapx;
                    $newInfo->save();
                }

            }else{
                return response()->json([
                    'status' => false,
                    'mess'=>'code wrong'
                ]);
            }
            return response()->json([
                'status' => true,
               
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'error' => $th->getMessage(),
                'status' => false
            ]);
         }
    }
    public function index(Request $request)
    {
        try{

            $adminId=Auth::guard('admin')->user()->adminid;
            $admin=Admin::where('adminid',$adminId)->first();
            $searchKeywords=$request->data;
            
            if($admin->depart_id==37 && $admin->status==1){
                if($admin->adminid!=2107)
                {
                    $dataVoucher=[];
                    $dataVoucherMain=[];
                   
                    $adminChild=Admin::where('leader',$adminId)->get();
                  
                    foreach($adminChild as $child){
                        $listVoucher=infoVoucher::with('admin')->where('admin_id',$child->adminid)
                        ->whereHas('admin', function ($q) use ($searchKeywords) {
                            $q->where('display_name', 'LIKE', '%' . $searchKeywords . '%')
                            ->orWhere('username', 'LIKE', '%' . $searchKeywords . '%');
                            
                        })
                        ->get();
                        foreach($listVoucher as $voucher){
                            $dataVoucher[]=$voucher;
                        }
                       
                    }
                    $listVoucher1=infoVoucher::with('admin')->where('admin_id',$adminId)
                    ->whereHas('admin', function ($q) use ($searchKeywords) {
                        $q->where('display_name', 'LIKE', '%' . $searchKeywords . '%')
                        ->orWhere('username', 'LIKE', '%' . $searchKeywords . '%');
                        
                    })
                    ->get();
                    foreach($listVoucher1 as $voucher){
                        $dataVoucherMain[]=$voucher;
                    }
                    
                    $listData=array_merge($dataVoucher,$dataVoucherMain);
                    $listData=$this->paginate($listData,10);
    
                    return response()->json([
                        'status'=>true,
                        'data'=>$listData
                    ]);

                }else{
                    $query=infoVoucher::with('admin')->orderBy('id','desc');
                    if($request->data == 'undefined' || $request->data =="")
                    {
                        $list=$query;
                    }
                    else{
                        $list=$query->where('name','like', '%' . $request->data . '%')
                        ->orWhere('phone','like', '%' . $request->data . '%')
                        ->orWhere('code','like', '%' . $request->data . '%')
                        ;
                    }
                    $info=$list->paginate(10);
                    return response()->json([
                        'status'=>true,
                        'data'=>$info
                    ]);
                }
               


            }
            else if($admin->depart_id==37 && $admin->status==2){
                $query=infoVoucher::with('admin')->where('admin_id',$adminId)->orderBy('id','desc');
                if($request->data == 'undefined' || $request->data =="")
                {
                    $list=$query;
                }
                else{
                    $list=$query->where('name','like', '%' . $request->data . '%')
                    ->orWhere('phone','like', '%' . $request->data . '%')
                    ->orWhere('code','like', '%' . $request->data . '%')
                    ;
                }
                $info=$list->paginate(10);
                return response()->json([
                    'status'=>true,
                    'data'=>$info
                ]);

            }else if($admin->depart_id==1){
                $query=infoVoucher::with('admin')->orderBy('id','desc');
                if($request->data == 'undefined' || $request->data =="")
                {
                    $list=$query;
                }
                else{
                    $list=$query->where('name','like', '%' . $request->data . '%')
                    ->orWhere('phone','like', '%' . $request->data . '%')
                    ->orWhere('code','like', '%' . $request->data . '%')
                    ;
                }
                $info=$list->paginate(10);
                return response()->json([
                    'status'=>true,
                    'data'=>$info
                ]);
            }
            else{
                return response()->json([
                    'status'=>true,
                    'mess'=>'no permission'
                ]);
            }

            // $query=infoVoucher::with('admin')->orderBy('id','desc');
            // if($request->data == 'undefined' || $request->data =="")
            // {
            //     $list=$query;
            // }
            // else{
            //     $list=$query->where('name','like', '%' . $request->data . '%')
            //     ->orWhere('phone','like', '%' . $request->data . '%')
            //     ->orWhere('code','like', '%' . $request->data . '%')
            //     ;
            // }
            
           

        }catch(\Throwable $th){
            return response()->json([
                'error' => $th->getMessage(),
                'status' => false
            ]);
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

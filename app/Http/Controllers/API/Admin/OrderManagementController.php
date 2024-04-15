<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\MemGroup;
use App\Models\Product;
use App\Models\ProductDesc;
use App\Models\CardPromotion;
use App\Models\OrderSum;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Exports\OrderSumExport;
use App\Models\IntroducePartner;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Rules\OrderManagementRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\OrderSumExcelExport;
use App\Models\Admin;
use App\Models\CouponDesUsing;
use App\Models\CouponDes;
use GuzzleHttp\Client;
use App\Models\Coupon;
use App\Models\BrandDesc;
use App\Models\CategoryDesc;
use App\Models\StatisticsPages;
use App\Mail\OrderBuyBackMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
// use Illuminate\Contracts\Encryption\DecryptException;


use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



class OrderManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Input: Status,mem_id,fromdate,todate,ordercode
     * OutPut: Result searching for a specific 
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
    public function totalOrderMonth(Request $request){
      
        $lastMonth=Carbon::now()->subMonth()->month;
       
        $currentMonth= Carbon::now()->format('m/Y');
       
       if(isset($request->month)){
        $month=$request->month;
       }
       else{
        $month=$currentMonth;
       }
      
       
        $orderSum=OrderSum::with('orderStatus','orderDetail','coupondesusing')
        ->get();
        
        foreach($orderSum as $index=> $item){
            if(($timestamp = strtotime($item->date_order)) !== false){
                $value=Carbon::parse($item->date_order)->format('m/d/Y');
               
                $orderSum[$index]['date_order']=$value;
                $orderSum[$index]['month']=Carbon::createFromFormat('m/d/Y',$orderSum[$index]['date_order'])->format('m/Y');
            }
            else
            {
                $orderSum[$index]['date_order']=date("m/d/Y",$item->date_order);
                $orderSum[$index]['month']=Carbon::createFromFormat('m/d/Y',$orderSum[$index]['date_order'])->format('m/Y'); 
            }
        }
        
        $orderSumList=[];
        $countProduct=[];
        foreach($orderSum as $index=> $item){
            if($item->month==$month){
                
                $orderSumList[]=$item;
            }
        }
      
        $totalSum=0;
        foreach($orderSumList as $sum){
            $totalSum+=$sum->total_price;
        }
       
        $price=number_format($totalSum, 0, '', ',').' VNĐ';
       
        return response()->json([
            'status'=>true,
            'totalSumMonth'=> $price,
            'totalorder'=>count($orderSumList),
            'month'=>$month
        ]);

    }
    public function reportOrderStatiscs(Request $request){
        $idAdmin = Auth::guard('admin')->user()->adminid;
        $AdminDepartment = Admin::find($idAdmin);
       
        $day=isset($request->day) ? $request->day : Carbon::now()->day;
        $month=isset($request->month) ? $request->month : Carbon::now()->month;
        $year=isset($request->year) ? $request->year :Carbon::now()->year;

        $fromDate =  isset($request['fromDate']) ?$request['fromDate'] : null;
        $toDate =  isset($request['toDate']) ? $request['toDate'] :null;
        if($fromDate=="Invalid date"){
            $fromDate=null;
        }
        if($toDate=="Invalid date"){
            $toDate=null;
        }
      
        $dataFromToDay=[
            'fromDate'=>$fromDate,
            'toDate'=>$toDate,
            'list'=>[],
            'count'=>0,
            'total'=>0
        ];
        $orderListDay=[
            'list'=>[],
            'total'=>0,
            'count'=>0,
            'day'=>$day
        ];
        $orderListMonth=[
            'list'=>[],
            'total'=>0,
            'count'=>0,
            'month'=>$month
        ];
        $orderListYear=[
            'list'=>[],
            'total'=>0,
            'count'=>0,
            'year'=>$year
        ];
        if($AdminDepartment->status != 2)
        {
           
            $listOrder = OrderSum::with('orderStatus')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $member = Member::select('mem_id')->where('company','=',Auth::guard('admin')->user()->adminid)->get();

            if(count($member)>0)
            {
                $listOrder=[];
                foreach ($member as $value) {
                    if(!isset($search['data']) && !isset($search['status']) &&  !isset($search['fromDate']) && !isset($search['toDate']))
                    {
                        $dataOrder= OrderSum::with('orderStatus')->where('mem_id',$value->mem_id)->get();
                        foreach($dataOrder as $item){
                            $listOrder[]=$item;
                        }
                    }
                }

            }
        }
           
        foreach($listOrder as $index=> $item){
            if(($timestamp = strtotime($item->date_order)) !== false){
                $value=Carbon::parse($item->date_order)->format('m/d/Y');
                $listOrder[$index]['date_order']=$value;
            }
            else
            {
                $listOrder[$index]['date_order']=date("m/d/Y",$item->date_order);
            }
            $listOrder[$index]['day']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('d');
            $listOrder[$index]['month']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('m');
            $listOrder[$index]['year']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('Y');
        }

        if(isset($fromDate) && isset($toDate)){
            foreach($listOrder as $value){
                if(strtotime($value['date_order'])>=strtotime($fromDate) && strtotime($value['date_order'])<=strtotime($toDate)){
                    $dataFromToDay['list'][]=$value;
                    $dataFromToDay['total']+=$value['total_price'];
                    $dataFromToDay['count']++;

                }
            }
        }
            
        foreach($listOrder as $index=>$item){
            if($item->year==$year){
                $orderListYear['list'][]=$item;
                $orderListYear['total']+=$item->total_price;
                $orderListYear['count']++;
                if($item->month==$month){
                    $orderListMonth['list'][]=$item;
                    $orderListMonth['total']+=$item->total_price;
                    $orderListMonth['count']++;
                    if($item->day==$day){
                        $orderListDay['list'][]=$item;
                        $orderListDay['total']+=$item->total_price;
                        $orderListDay['count']++;
                    }
                }
            }
        }
        return response()->json([
            'status'=>true,
            'day'=>$day,
            'month'=>$month,
            'year'=>$year,
            'orderListMonth'=> $orderListMonth,
            'orderListDay'=> $orderListDay,
            'orderListYear'=> $orderListYear,
            'dataFromToDay'=>$dataFromToDay,
            
        ]);

    }
    public function reportStatiscsPage(Request $request){
       
         //thông kê truy cập theo ngày theo tuần theo tháng
         $statisCheck=[
            'statisCheckDay'=>'',
            'statisCheckMonth'=>'',
            'statisCheckWeek'=>'',
        ];

        //day
        $dateToCheck = Carbon::now()->toDateString();
        $statisCheckDay=StatisticsPages::where('module','product')
        ->where('date',$dateToCheck)->orderBy('count','desc')->take(10)->get();
        
        
       
        //month
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); 
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        $statisCheckMonth=StatisticsPages::where('module','product')
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->orderBy('count','desc')->take(10)
        ->get();
       

        //week
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d'); 
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d'); 

        $statisCheckWeek=StatisticsPages::where('module','product')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->orderBy('count','desc')->take(10)
        ->get();
    
        $statisCheck=[
            'statisCheckDay'=>$statisCheckDay,
            'statisCheckWeek'=>$statisCheckWeek,
            'statisCheckMonth'=>$statisCheckMonth,
        ];
        return response()->json([
            'status'=>true,
            'statisCheck'=>$statisCheck
        ]);

    }
    public function reportCategory(Request $request){
         //danh mục nào khách hàng quan tâm nhiều nhất (3 cái)

         $StatisticsCateDesc=StatisticsPages::where('module','category')
         ->select('uri',DB::raw('SUM(count) as count'),'module')
         ->groupBy('uri','module')
         ->orderBy('count','desc')->take(3)->get();
         
        
         $urlCateDesc='';
         $highCategory=[];
        
         foreach($StatisticsCateDesc as $item){
             $urlCateDesc=$item->uri;
             $highCategory[]=CategoryDesc::with('category')
             ->where('friendly_url',$urlCateDesc)->first();
         }
       
         //danh mục nào khách hàng ít quan tâm nhất
 
         $StatisticsCateAsc=StatisticsPages::where('module','category')
         ->select('uri',DB::raw('SUM(count) as count'),'module')
         ->groupBy('uri','module')->orderBy('count','asc')
         ->take(3)->get();
       
        
         $urlCateAsc='';
         $lowCategory=[];
        //return response()->json(is_array($highCategory));
         foreach($StatisticsCateAsc as $item){
             $urlCateAsc=$item->uri;
             $lowCategory[]=CategoryDesc::with('category')
             ->where('friendly_url', $urlCateAsc)->first();
         }
         
        return response()->json([
            'status'=>true,
            'highCategory'=>$highCategory,
            'lowCategory'=>$lowCategory
        ]);
    }
    public function reportBestProduct(Request $request){
        $queryProduct=StatisticsPages::where('module','product')
        ->select('uri',DB::raw('SUM(count) as count'),'module')
        ->groupBy('uri','module');
       
    
       $StatisticsProduct=$queryProduct->orderBy('count','desc')->get();
      
    
       $urlProduct='';
       $bestProduct=[];
      
       $count=0;
       
       foreach( $StatisticsProduct as $item){
          
            $productExit=ProductDesc::with('product')
           ->where('friendly_url',$item->uri)->first();
           if(isset($productExit)){
            $count++;
            $bestProduct[]=$productExit;
           }
            if($count==3){
                break;
            }
       }
       return response()->json([
        'status'=>true,
        'bestProduct'=>$bestProduct
       ]);

    }
   
    public function reportBestSale(Request $request){
         //top 3 sales bán hàng doanh số cao nhất

        //  $adminSale=Admin::orderBy('adminid','desc')->with('member')->get();
        
         
        //  foreach($adminSale as $index=> $admin){
            
        //      if(count($admin->member)!=0){
        //         $total=0;
        //         $count=0;
        //          foreach($admin->member as $item){
        //              $dataOrder= OrderSum::with('orderStatus')->where('mem_id',$item->mem_id)->get();
        //                  foreach($dataOrder as  $item){
        //                      $total+=$item['total_price'];
        //                      $count++;
        //                 }
        //         }
        //         $orderSale[]=[
        //              'adminId'=>$admin->adminid,
        //              'adminName'=>$admin->username,
        //              'total'=>$total,
        //              'count'=>$count
        //         ];
                
        //     }
        // }
        
        // usort($orderSale, fn($a, $b) => $b['total']- $a['total']);
       
        // $orderBestSale=array_slice($orderSale, 0, 3);
       
        // return response()->json([
        //     'status'=>true,
        //     'orderBestSale'=> $orderBestSale
        //  ]);
        $adminSale = Admin::whereHas('member')->with('member')->orderBy('adminid', 'desc')->get();
       foreach($adminSale as $index=> $admin){
            $total = 0;
            $count = 0;
            
            $memberIds = $admin->member->pluck('mem_id')->toArray();
            
            $dataOrder = OrderSum::with('orderStatus')
                ->whereIn('mem_id', $memberIds)
                ->get();
                
            foreach ($dataOrder as $item) {
                $total += $item['total_price'];
                $count++;
            }
            
            $orderSale[] = [
                'adminId' => $admin->adminid,
                'adminName' => $admin->username,
                'total' => $total,
                'count' => $count
            ];
       }
       usort($orderSale, fn($a, $b) => $b['total'] - $a['total']);

        $orderBestSale = array_slice($orderSale, 0, 3);

        return response()->json([
            'status' => true,
            'orderBestSale' => $orderBestSale
        ]);
       

    }
    public function reportCheckMember(Request $request){
        $listOrder = OrderSum::with('orderStatus')
        ->orderBy('created_at','desc')
        ->get();
        
        foreach($listOrder as $index=> $item){
            if(($timestamp = strtotime($item->date_order)) !== false){
                $value=Carbon::parse($item->date_order)->format('m/d/Y');
                $listOrder[$index]['date_order']=$value;
            }
            else
            {
                $listOrder[$index]['date_order']=date("m/d/Y",$item->date_order);
            }
            $listOrder[$index]['day']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('d');
            $listOrder[$index]['month']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('m');
            $listOrder[$index]['year']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('Y');
        }
        

          // khách hàng nào chưa phát sinh đơn hàng trong 1 tháng qua (top 10 , và ramdom ngẫu nhiên khách hàng theo tháng )
        $thirtyDaysAgo = Carbon::now()->subDays(30)->format('m/d/Y');
        $sixtyDaysAgo = Carbon::now()->subDays(60)->format('m/d/Y');
        $DaysAgo = Carbon::now()->subDays(100)->format('m/d/Y');
       
        
        $now=Carbon::now()->format('m/d/Y');
        

        $listMenMonth=[];

        foreach($listOrder as $value){
            if(strtotime($value['date_order'])>=strtotime( $thirtyDaysAgo) && strtotime($value['date_order'])<=strtotime($now) && $value['mem_id']!=0){
                if(!in_array($value->mem_id,$listMenMonth)){
                      array_push($listMenMonth,$value->mem_id);
                }
                  
            }
        }
       

        $listMenNoMonth=[];
        foreach($listOrder as $value)
        {
            if(strtotime($value['date_order'])>=strtotime( $sixtyDaysAgo) && strtotime($value['date_order'])<strtotime( $thirtyDaysAgo) && $value['mem_id']!=0){
                if(!in_array($value->mem_id,$listMenMonth) && !in_array($value->mem_id,$listMenNoMonth)){
                      array_push($listMenNoMonth,$value->mem_id);
                }
                  
            }
        }
       
        $arrLastDay=[];
        foreach($listOrder as $order){
           if($order['mem_id']==175){
            $arrLastDay[]=$order;
           }
        }

       
        usort($arrLastDay, fn($a, $b) => strtotime($b['date_order'])- strtotime($a['date_order']));
      
         
        $dataMenNoMonth=[];
        foreach($listMenNoMonth as $item){
            $lastDayOrder=[];
            foreach($listOrder as $order){
                if($order['mem_id']==$item && $order['year']<=Carbon::now()->year){
                    $lastDayOrder[]=$order;
                }
            }
            usort($lastDayOrder, fn($a, $b) => strtotime($b['date_order'])- strtotime($a['date_order']));
            $lastDay=$lastDayOrder[0]['date_order'];
            $member=Member::with('repurchase')->where('mem_id',$item)->first();
           
            if($member)
            {
                $memItems=[
                    'MaKH'=>$member->MaKH,
                    'd_name'=>$member->username,
                    'd_phone'=>$member->phone,
                    'latsDay'=>$lastDay
                ];
                array_push( $dataMenNoMonth, $memItems);
            }
        }
       
       
       
         //khách hàng nào có số lượng tiền mua hàng giảm so với tháng trước
           
        $sumMemberMonth=[];
        
      
        foreach($listMenMonth as $member){
            $sumOrder=0;
            $arraySum=[];
            foreach($listOrder as $order){
                if($order['mem_id']==$member && strtotime($order['date_order'])>=strtotime( $thirtyDaysAgo) && strtotime($order['date_order'])<=strtotime($now) && $order['mem_id']!=0){
                     $sumOrder+=$order['total_price'];
                     $arraySum[]=$order['total_price'];
                    
                }
            }
            
            $sumMemberMonth[]=[
                 'mem_id'=>$member,
                 'totalOrder'=>$sumOrder
            ];
         }
       
        $sumMemberTwoMonth=[];
       
        foreach($listMenMonth as $member){
            $sumOrder=0;
          
            foreach($listOrder as $value)
             {
                if( $value['mem_id']==$member && strtotime($value['date_order'])>=strtotime( $sixtyDaysAgo) && strtotime($value['date_order'])<strtotime( $thirtyDaysAgo) && $value['mem_id']!=0){
                    $sumOrder+=$value['total_price'];
                }
             }
          
            if($sumOrder>0)
            {
                $sumMemberTwoMonth[]=[
                    'mem_id'=>$member,
                    'totalOrder'=>$sumOrder
                ];
            }
           
         }
        
        // return $arrayDate;
         //1794 247073000
      
        $memLowOrderMonth=[];
        // return $sumMemberTwoMonth;  //1794  208612000     1788  209175000
        // return $sumMemberMonth;       // 1794  2946690000        179548432

       
        foreach($sumMemberTwoMonth as $item)
         {
            foreach($sumMemberMonth as $item1){
                if($item['mem_id']==$item1['mem_id'] && $item['totalOrder']>$item1['totalOrder'])
                {
                    $member=Member::where('mem_id',$item['mem_id'])->first();
                    $memItem=[
                        'MaKH'=>$member->MaKH,
                        'd_name'=>$member->username,
                        'd_phone'=>$member->phone,
                        'totalOrderCurrentMonth'=>$item1['totalOrder'],
                        'totalOrderLastMonth'=>$item['totalOrder']
                    ];
                    $memLowOrderMonth[]=$memItem;
                }
            }
        }
       

        return response()->json([
            'status'=>true,
            'dataMenNoMonth'=>$dataMenNoMonth,
            'memLowOrderMonth'=>$memLowOrderMonth
        ]);

    }
  
    public function index(Request $request)
    {

        DB::table('adminlogs')->insert([
            'adminid' => Auth::guard('admin')->user()->adminid,
            'time' => Carbon::now(),
            'ip'=> $request->ip(),
            'action'=>'show',
            'cat'=>'order',
            'pid'=> $request->ip(),
        ]);
        $idAdmin = Auth::guard('admin')->user()->adminid;
        $AdminDepartment = Admin::find($idAdmin);
        $search= $request->all();

        if($AdminDepartment->status != 2)
        {
             if(!isset($search['data']) && !isset($search['status']) &&  !isset($search['fromDate']) && !isset($search['toDate']))
            {
                $listOrder = OrderSum::with('orderStatus')
                ->orderBy('created_at','desc')
                ->paginate(10);
                return response()->json([
                    'listOrder'=>$listOrder,
                    'countlistOrder'=>count($listOrder),
                ]);
            }
            else
            {
                DB::table('adminlogs')->insert([
                    'adminid' => Auth::guard('admin')->user()->adminid,
                    'time' => Carbon::now(),
                    'ip'=> $request->ip(),
                    'action'=>'search',
                    'cat'=>'order',
                    'pid'=> $request->ip(),
                ]);
                $name =  isset($search['data']) ? $search['data'] : ''; 
                $status =   isset($search['status']) ?  $search['status'] : ''; 
                $fromDate =  isset($search['fromDate']) ?$search['fromDate'] : null;
                $toDate =  isset($search['toDate']) ? $search['toDate'] :null;
                if($fromDate=="Invalid date"){
                    $fromDate=null;
                }
                if($toDate=="Invalid date"){
                    $toDate=null;
                }
                $collection=OrderSum::with('orderStatus')->where(function ($query) use ($name){
                    $query->where('d_name', 'LIKE', '%' . $name . '%')
                       ->orWhere('d_phone', 'LIKE', '%' . $name . '%')
                        ->orWhere('order_code', 'LIKE', '%' . $name . '%');
                })->where('status','LIKE', '%' . $status . '%')
                ->orderBy('date_order','desc');
               
                if(!isset($fromDate) && !isset($toDate)){
                    $listOrder= $collection->paginate(10);
                    return response()->json([
                        'listOrder'=>$listOrder,
                        'countlistOrder'=>count($listOrder),
                    ]);
                }else{
                    $orderSum=$collection->get();
                    foreach($orderSum as $index=> $item){
                        if(($timestamp = strtotime($item->date_order)) !== false){
                            $value=Carbon::parse($item->date_order)->format('m/d/Y');
                            $orderSum[$index]['date_order']=$value;
                        }
                        else
                        {
                            $orderSum[$index]['date_order']=date("m/d/Y",$item->date_order);
                        }
                    }
                    $data=[];
                    if(isset($fromDate) && isset($toDate)){
                        foreach($orderSum as $value){
                            if(strtotime($value['date_order'])>=strtotime($fromDate) && strtotime($value['date_order'])<=strtotime($toDate)){
                                $data[]=$value;
                            }
                        }
                    }
                    if(!isset($fromDate) && isset($toDate)){
                        foreach($orderSum as $value){
                            if(strtotime($value['date_order'])<=strtotime($toDate)){
                                $data[]=$value;
                            }
                        }
                    }
                    if(isset($fromDate) && !isset($toDate)){
                        foreach($orderSum as $value){
                            if(strtotime($value['date_order'])>=strtotime($fromDate)){
                                $data[]=$value;
                            }
                        }
                    }
                    $dataList=$this->paginate($data,10);
                    
                    return response()->json([
                        'listOrder'=>$dataList,
                        'countlistOrder'=>count($dataList),
                    ]);
                }
            }
        }else{
        $member = Member::select('mem_id')->where('company','=',Auth::guard('admin')->user()->adminid)->get();
        if(count($member)>0)
        {
            $listOrder=[];
            foreach ($member as $value) {
                if(!isset($search['data']) && !isset($search['status']) &&  !isset($search['fromDate']) && !isset($search['toDate']))
                {
                    $dataOrder= OrderSum::with('orderStatus')->where('mem_id',$value->mem_id)->get();
                    foreach($dataOrder as $item){
                        $listOrder[]=$item;
                    }
                }
                else
                {
                    DB::table('adminlogs')->insert([
                        'adminid' => Auth::guard('admin')->user()->adminid,
                        'time' => Carbon::now(),
                        'ip'=> $request->ip(),
                        'action'=>'search',
                        'cat'=>'order',
                        'pid'=> $request->ip(),
                    ]);
                    $name =  isset($search['data']) ? $search['data'] : ''; 
                    $status =   isset($search['status']) ?  $search['status'] : ''; 
                    $fromDate =  isset($search['fromDate']) ?$search['fromDate'] : null;
                    $toDate =  isset($search['toDate']) ? $search['toDate'] :null;
                    if($fromDate=="Invalid date"){
                        $fromDate=null;
                    }
                    if($toDate=="Invalid date"){
                        $toDate=null;
                    }
                    $collection=OrderSum::with('orderStatus')->where('mem_id',$value->mem_id)
                    ->where(function ($query) use ($name){
                        $query->where('d_name', 'LIKE', '%' . $name . '%')
                           ->orWhere('d_phone', 'LIKE', '%' . $name . '%')
                            ->orWhere('order_code', 'LIKE', '%' . $name . '%');
                    })->where('status','LIKE', '%' . $status . '%')
                    ->orderBy('created_at','desc');
                    if(!isset($fromDate) && !isset($toDate)){
                        $dataOrder= $collection->get();
                        foreach($dataOrder as $item){
                            $listOrder[]=$item;
                        }
                    }
                    else{
                        $orderSum=$collection->get();
                        foreach($orderSum as $index=> $item){
                            if(($timestamp = strtotime($item->date_order)) !== false){
                                $value=Carbon::parse($item->date_order)->format('m/d/Y');
                                $orderSum[$index]['date_order']=$value;
                            }
                            else
                            {
                                $orderSum[$index]['date_order']=date("m/d/Y",$item->date_order);
                            }
                        }
                        $data=[];
                        if(isset($fromDate) && isset($toDate)){
                            foreach($orderSum as $value){
                                if(strtotime($value['date_order'])>=strtotime($fromDate) && strtotime($value['date_order'])<=strtotime($toDate)){
                                    $listOrder[]=$value;
                                }
                            }
                        }
                        if(!isset($fromDate) && isset($toDate)){
                            foreach($orderSum as $value){
                                if(strtotime($value['date_order'])<=strtotime($toDate)){
                                    $listOrder[]=$value;
                                }
                            }
                        }
                        if(isset($fromDate) && !isset($toDate)){
                            foreach($orderSum as $value){
                                if(strtotime($value['date_order'])>=strtotime($fromDate)){
                                    $listOrder[]=$value;
                                }
                            }
                        }
                    }
                }
            }
            $listOrder=$this->paginate($listOrder,10);
              return response()->json([
                    'listOrder'=>$listOrder,
                    'countlistOrder'=>count($listOrder),
                ]);
        }
        else
        {
            return response()->json([
                'status'=>true,
                'message'=>'Bạn không có đơn hàng nào!!!'
            ]);
        }
        }
    }
    public function export(Request $request)
    {
        DB::table('adminlogs')->insert([
            'adminid' => Auth::guard('admin')->user()->adminid,
            'time' => Carbon::now(),
            'ip'=> $request->ip(),
            'action'=>'excel',
            'cat'=>'order',
            'pid'=> $request->ip(),
        ]);
      
        $dataKey = json_decode($request['key']);
        $value=null;
        foreach($dataKey as $item){
            $value=[
                'fromDate'=>$item->fromDate,
                'toDate'=>$item->toDate,
                'status'=>$item->status,
            ];
        }
        $fromDate=$value['fromDate'];
        $toDate=$value['toDate'];
        $status=$value['status'];
        $orderSum=OrderSum::get();
        $list=[];
        foreach($orderSum as $index=> $item){
            if(($timestamp = strtotime($item->date_order)) !== false){
                $value=Carbon::parse($item->date_order)->format('m/d/Y');
                $orderSum[$index]['date_order']=$value;
            }
            else
            {
                $orderSum[$index]['date_order']=date("m/d/Y",$item->date_order);
            }
        }
        $options=[
            1=>'Chờ xử lý',
            2=>'Chờ thanh toán',
            3=>'Đã thanh toán',
            4=>'Đã giao hàng',
            5=>'Đã hoàn tất',
            6=>'Đã hủy bỏ',
            7=>'Khách hàng đã hủy bỏ',
            8=>'Khách hàng đã mua lại'
        ];
      
        $dataList=[];
        foreach( $orderSum as $item){
            $dataList[]= [
                'orderId'=>$item->order_id,
                'orderCode'=>$item->order_code,
                'name'=>$item->d_name,
                'address'=>$item->d_address,
                'phone'=>$item->d_phone,
                'email'=>$item->d_email,
                'companyName'=>$item->c_name,
                'companyAddress'=>$item->c_address,
                'companyPhone'=>$item->c_phone,
                'companyEmail'=>$item->c_email,
                'totalCart'=>$item->total_cart,
                'totalPrice'=>$item->total_price,
                'shippingMethod'=>$item->shipping_method,
                'paymentMethod'=>$item->payment_method,
                'dateOrder'=> $item->date_order,
                'shipDate'=>$item->ship_date,
                'status'=>$options[$item->status],
                'date_order_status1'=>$item->date_order_status1,
                'date_order_status2'=>$item->date_order_status2,
                'date_order_status3'=>$item->date_order_status3,
                'date_order_status4'=>$item->date_order_status4,
                'date_order_status5'=>$item->date_order_status5,
                'date_order_status6'=>$item->date_order_status6,
                'date_order_status7'=>$item->date_order_status7,

                'date_order_2_1'=>(isset($item->date_order_status1) && isset($item->date_order_status2)) ? Carbon::parse($item->date_order_status2)->diffInMinutes(Carbon::parse($item->date_order_status1)) : 0,
                'date_order_3_2'=>(isset($item->date_order_status2) && isset($item->date_order_status3)) ? Carbon::parse($item->date_order_status3)->diffInMinutes(Carbon::parse($item->date_order_status2)) : 0,
                'date_order_4_3'=>(isset($item->date_order_status3) && isset($item->date_order_status4)) ? Carbon::parse($item->date_order_status4)->diffInMinutes(Carbon::parse($item->date_order_status3)) : 0,
                'date_order_5_4'=>(isset($item->date_order_status4) && isset($item->date_order_status5)) ? Carbon::parse($item->date_order_status5)->diffInMinutes(Carbon::parse($item->date_order_status4)) : 0,
                'date_order_6_5'=>(isset($item->date_order_status5) && isset($item->date_order_status6)) ? Carbon::parse($item->date_order_status6)->diffInMinutes(Carbon::parse($item->date_order_status5)) : 0,
                'date_order_7_6'=>(isset($item->date_order_status6) && isset($item->date_order_status7)) ? Carbon::parse($item->date_order_status7)->diffInMinutes(Carbon::parse($item->date_order_status6)) : 0,
            ];
        }
       
        $data=[];
        if($status!=""){
            if(isset($fromDate) && isset($toDate)){
                foreach($dataList as $value){
                
                    if(strtotime($value['dateOrder'])>=strtotime($fromDate) && strtotime($value['dateOrder'])<=strtotime($toDate) && $value['status']==$options[$status]){
                    
                        $data[]=$value;
                    }
                    
                }
                
            }
            if(!isset($fromDate) && isset($toDate)){
                foreach($dataList as $value){
                    if(strtotime($value['dateOrder'])<=strtotime($toDate) && $value['status']==$options[$status]){
                    
                        $data[]=$value;
                    }
                }
            }
            if(isset($fromDate) && !isset($toDate)){
                foreach($dataList as $value){
                    if(strtotime($value['dateOrder'])>=strtotime($fromDate) && $value['status']==$options[$status]){
                      
                        $data[]=$value;
                    }
                }
            }
            if(!isset($fromDate) && !isset($toDate)){
                foreach($dataList as $value){
                    if($value['status']==$options[$status]){
                        $data[]=$value;
                    }
                }
               
            }
        }
        else{
            if(isset($fromDate) && isset($toDate)){
                foreach($dataList as $value){
                
                    if(strtotime($value['dateOrder'])>=strtotime($fromDate) && strtotime($value['dateOrder'])<=strtotime($toDate)){
                    
                        $data[]=$value;
                    }
                    
                }
            }
        
            if(!isset($fromDate) && isset($toDate)){
                foreach($dataList as $value){
                    if(strtotime($value['dateOrder'])<=strtotime($toDate)){
                    
                        $data[]=$value;
                    }
                }
               
            }
            if(isset($fromDate) && !isset($toDate)){
                foreach($dataList as $value){
                    if(strtotime($value['dateOrder'])>=strtotime($fromDate)){
                        //return 1113;
                        $data[]=$value;
                    }
                }
            
            }
            if(!isset($fromDate) && !isset($toDate)){

                $data=$dataList;
            }

        }
        $fileName = 'order_' . date('Y_m_d_H_i_s') . '.xlsx';
        $export = new OrderSumExcelExport($data);

        $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
     
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];
    
        return response($fileContents, 200, $headers);
    }
    public function searchMembersOrder(){

        try{
            //return Auth::guard('admin')->user()->adminid;
            $listMember = Member::with('repurchase.orderDetail.product.productDesc')->
            where('company',Auth::guard('admin')->user()->adminid)->get();
           
           

            return response()->json([
                'member'=>$listMember,
                'status' => true
            ]);
        }catch(\Throwable $e){
            return response()->json([
                'error' => $e->getMessage(),
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
        $orderSumId = OrderSum::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $orderDetail= OrderDetail::where('order_id',$id)->with('product','productDesc')->get();
        // return  $orderDetail;
        $orderSum = OrderSum::find($id);
        $orderStatus = OrderStatus::find($orderSum->status);
        //  foreach ($orderDetail as $value) {
        //     $orderProduct[] = Product::where('product_id',$value->item_id)->first();
        //  }
        $orderProductDesc=[];
        foreach ($orderDetail as $value) {
            $orderProductDesc[] = ProductDesc::where('product_id',$value->item_id)->get();
        }
        $orderCard = CardPromotion::where('order_id',$orderSum->order_id)->get();
        return response()->json([
            'orderSumId' => $orderSum,
            'orderDetail' => $orderDetail,
            'orderStatus' => $orderStatus,
            // 'product' => $orderProduct,
            'product_desc' => $orderProductDesc,
            'card' => $orderCard,  
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCoupon(){
        $now = date('Y-m-d H:i:s');
        $stringTime = strtotime($now);
        $dataForYou =[];
        $dataPublic =[];
        $listCouponForYou = Coupon::with('couponDesc')->orderBy('id','DESC')
        ->where('StartCouponDate','<=',$stringTime)
        ->where('EndCouponDate','>=',$stringTime)
        ->get();
        $dataForYou =[];
        $dataPublic =[];

        if (count($listCouponForYou) > 0) {
        foreach ($listCouponForYou as $coupon)
        {
            $catId = $coupon->DanhMucSpChoPhep;
            $category = [];
        
            $id = explode(',',$catId);
            $category = CategoryDesc::whereIn('cat_id',$id)->get();

            $brandId = $coupon->ThuongHieuSPApDung;
            $brand = [];

            $id = explode(',',$brandId);
            $brand = BrandDesc::whereIn('brand_id',$id)->get();

            $dataForYou[] = [
                'id' => $coupon->id,
                'TenCoupon' => $coupon->TenCoupon,
                'MaPhatHanh' => $coupon->MaPhatHanh,
                'StartCouponDate' => $coupon->StartCouponDate,
                'EndCouponDate' => $coupon->EndCouponDate,
                'DesCoupon' => $coupon->DesCoupon,
                'GiaTriCoupon' => $coupon->GiaTriCoupon,
                'MaxValueCoupon' => $coupon->MaxValueCoupon,
                'SoLanSuDung' => $coupon->SoLanSuDung,
                'MaKhoSPApdung' => $coupon->MaKhoSPApdung,
                'KHSuDungToiDa' => $coupon->KHSuDungToiDa,
                'SuDungDongThoi' => $coupon->SuDungDongThoi,
                'DonHangChapNhanTu' => $coupon->DonHangChapNhanTu,
                'categoryName' => $category->pluck('cat_name'),
                'friendlyUrlCat' => $category->pluck('friendly_url'),
                'brandName' => $brand->pluck('title'),
                'brandFriendlyUrl' => $brand->pluck('friendly_url'),
                'dataCouponDesc' => $coupon->couponDesc?? null,
                'couponType' =>$coupon->CouponType,
            ];
        }  
        }
        else
        {
            $listCouponPublic = Coupon::with('couponDesc')->orderBy('id','DESC')
            ->where('StartCouponDate','<=',$stringTime)
            ->where('EndCouponDate','>=',$stringTime)->get();
            if (count($listCouponPublic) > 0) {
            foreach ($listCouponPublic as $coupon)
            {
                $catId = $coupon->DanhMucSpChoPhep;
                $category = [];

                    $id = explode(',',$catId);
                    $category = CategoryDesc::whereIn('cat_id',$id)->get();

                $brandId = $coupon->ThuongHieuSPApDung;
                $brand = [];

                    $id = explode(',',$brandId);
                    $brand = BrandDesc::whereIn('brand_id',$id)->get();

                $dataPublic[] = [
                    'id' => $coupon->id,
                    'TenCoupon' => $coupon->TenCoupon,
                    'MaPhatHanh' => $coupon->MaPhatHanh,
                    'StartCouponDate' => $coupon->StartCouponDate,
                    'EndCouponDate' => $coupon->EndCouponDate,
                    'DesCoupon' => $coupon->DesCoupon,
                    'GiaTriCoupon' => $coupon->GiaTriCoupon,
                    'MaxValueCoupon' => $coupon->MaxValueCoupon,
                    'MaKhoSPApdung' => $coupon->MaKhoSPApdung,
                    'SoLanSuDung' => $coupon->SoLanSuDung,
                    'KHSuDungToiDa' => $coupon->KHSuDungToiDa,
                    'SuDungDongThoi' => $coupon->SuDungDongThoi,
                    'DonHangChapNhanTu' => $coupon->DonHangChapNhanTu,
                    'categoryName' => $category->pluck('cat_name'),
                    'friendlyUrlCat' => $category->pluck('friendly_url'),
                    'brandName' => $brand->pluck('title'),
                    'brandFriendlyUrl' => $brand->pluck('friendly_url'),
                    'dataCouponDesc' => $coupon->couponDesc?? null,
                    'couponType' =>$coupon->CouponType,
                
                    ];
                }  
            }           
        }
        return response()->json([
            'you'=>$dataForYou,
            'pub'=>$dataPublic,
        ]);
        // }
    }
    public function getDelivery(Request $request){
        try{
            $orderSum = OrderSum::where('sopx',$request->sopx)->first();
            if($orderSum){
                $orderStatus=OrderStatus::where('status_id',$request->status)->first();
                if( isset($orderStatus)){
                    $orderSum->status=isset($request->status) ? $request->status: '';
                }
                else{
                    return response()->json([
                        'status'=>false,
                        'message'=>false
                    ]);
                }
                $orderSum->save();
                return response()->json([
                    'status'=>$orderSum->status,
                    'message'=>"success"
                ]);
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>false
                ]);
            }
           
            
         }
         catch(\Exception $e){
                 return response()->json([
                     'status' => false,
                     'message' => $e->getMessage()
                 ], 422);
         }
    }

    public function update(Request $request, $id)
    {
     
       $validator = OrderManagementRequest::validate($request->all());
      
        $data = $request->all();
       
        if($validator->fails()){
            return response()->json([
                'message'=>'Validations fails',
                'errors'=>$validator->errors()
            ],422);
        }

        
        $orderSumId = OrderSum::find($id);
        $status= $data['status'];
        $comment=$data['comment'];
        $delivery = $data['delivery'];
        if($data['status']==8){
            $status=7;
        }
        //4/4/2024
        // if(isset($orderSumId->member)){
        //     $orderDetail= OrderDetail::where('order_id',$id)->with('product','productDesc')->get();
        //     $orderProductDesc=[];
        //     foreach ($orderDetail as $value) {
        //         $orderProductDesc[]=[
        //             "maHH"=> $value->product->macn,
        //             "tenHH" => $value->productDesc->title,
        //             "soluong" => $value->quantity,
        //             "dongia" =>$value->item_price
        //         ];
        //     }
            
        //     $result = [
        //     "token" => "nocheck",
        //     "maKH" => $orderSumId->member->MaCC,
        //     "diachiGiaohang" => $orderSumId->member->DiaChiGiaoHang,
        //     "diachiHoadon" => $orderSumId->member->DiaChiHoaDon,
        //     "ngayGiao" => Carbon::now(),
        //     "chitiet" =>$orderProductDesc
        //     ];
           
        // }
       
       
        // $endpoint ='http://server3.onesystem.vn:189/NKC/Web/CreateVoucher';
        // $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
        // $client = new Client();
        // $response = $client->request('POST', $endpoint, ['headers' => $headers, 'body' => json_encode($result)]);
       
       
        // $responseData = json_decode($response->getBody(), true);
       
       
        

        // $sopx='';
        // if(isset($responseData['sopx'])){
        //     $sopx = $responseData['sopx'];
        // }
        // $sopx = $responseData['sopx'];
        
    
        $orderSumId->update([
            'status'=> $status,
            'comment'=>$comment,
            'phieu_xuat'=>$delivery,
            // 'sopx'=> $sopx
        ]);
       
       
        $admin=Admin::where('adminid',Auth::guard('admin')->user()->adminid)->first();
        //return $admin;
        $description=$admin->username." :Cập nhật đơn hàng" .$id.
        ", sửa hàng ngày ".Carbon::now().
        ", link là: http://adminvtnk.ddns.net/order/edit/".$id ;
        DB::table('adminlogs')->insert([
        'adminid' => Auth::guard('admin')->user()->adminid,
        'time' => Carbon::now(),
        'ip'=> $request->ip(),
        'action'=>'update',
        'cat'=>'order',
        'pid'=> $request->ip(),
        'description'=> $description
        ]);
     
        $orderSum=OrderSum::where('order_id',$id)->first();
         switch ($data['status']) {
            case 1:
                $orderSum->date_order_status1=Carbon::now();
                break;
            case 2:
                $orderSum->date_order_status2=Carbon::now();
                break;
            case 3:
                $orderSum->date_order_status3=Carbon::now();
                break;
            case 4:
                $orderSum->date_order_status4=Carbon::now();
                break;
            case 5:
                $orderSum->date_order_status5=Carbon::now();
                break;
            case 6:
                $orderSum->date_order_status6=Carbon::now();
                break;
            case 7:
                $orderSum->date_order_status7=Carbon::now();
                break;
        }
        $orderSum->save();
        return response()->json([
            'status' => true
        ]);
    }
    public function getProductSAP(Request $request){
        try{
          
            $endpoint = 'http://192.168.117.222:8090/NKC/Web/GetListItemSync?type=tatca
            ';
            $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
            $client = new Client();
            $response = $client->request('GET', $endpoint, ['headers' => $headers]);
            $responseData = json_decode($response->getBody(), true);
            $arrData=[];
            $arrData1=[];
            if(isset($responseData)){
                foreach($responseData as $item){
                    $existProduct=Product::where('macn',$item['MaHH'])->first();
                    if( isset($existProduct)){
                        $arrData[]=$item['MaHH'];
                    }else{
                        $arrData1[]=$item['MaHH'];
                    }
                }
            }
            return response()->json([
                'status'=>true,
                'data'=>$arrData,
                'data1'=> $arrData1
            ]);



        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    public function createMemberSAP(Request $request){
        try{
           
            $endpoint = 'http://192.168.117.222:8090/NKC/Web/GetListBPSync?type=tatca';
            $client = new Client(['timeout' => 30]);

            $response = $client->request('GET', $endpoint);
            $responseData = json_decode($response->getBody(), true);
           
            $maKHList = array_column($responseData, 'MaKH');
          
            // $modifiedMaKHList = array_map(function ($maKH) {
            //     return substr($maKH, 2);
            // }, $maKHList);
          

            $existingMaKHList = Member::pluck('MaKH')->toArray();
           

            $newMaKHList = array_diff($maKHList, $existingMaKHList);
           

            foreach ($responseData as $item) {
                $makh = $item['MaKH'];
                if (in_array($makh, $newMaKHList)) {
                    $member = new Member();
                    $member->MaKH=$item['MaKH'];
                    $member->MaCC=$item['MaCC'];
                    $member->DiaChi=$item['DiaChi'];
                    $member->TenKH=$item['TenKH'];
                    $member->MST=$item['MST'];
                    $member->Dienthoai=$item['Dienthoai'];
                    $member->NguoiLienHe=$item['NguoiLienHe'];
                    $member->Email=$item['Email'];
                    $member->DiaChiHoaDon=$item['DiaChiHoaDon'];
                    $member->DiaChiGiaoHang=$item['DiaChiGiaoHang'];
                    $member->save();
                }
            }
            return response()->json([
                'status' => true,
            ]);
        
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    public function getMemberSAP(Request $request){
        try{
        //     $endpoint = 'http://192.168.117.222:8090/NKC/Web/GetListBPSync?type=tatca';
        //     $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
        //     $client = new Client();
        //     $response = $client->request('GET', $endpoint, ['headers' => $headers, 'timeout' => 30]);
        //     $responseData = json_decode($response->getBody(), true);
        //     $arrData=[];
        //     $arrData1=[];
           
        //     if(isset( $responseData )){
        //     foreach($responseData as $item){
        //         $makh=substr($item['MaKH'], 2);
        //         $existMember=Member::where('MaKH',$makh)->first();
        //        if(isset($existMember)){
        //         $arrData[]=$item['MaCC'];
               
        //        }
        //     }
        //    }
        //    return response()->json([
        //     'status'=>true,
        //     'data'=>$arrData,
        //    ]);
        $endpoint = 'http://192.168.117.222:8090/NKC/Web/GetListBPSync?type=tatca';
        $client = new Client(['timeout' => 30]);

        $response = $client->request('GET', $endpoint);
        $responseData = json_decode($response->getBody(), true);
        // return count( $responseData); //1736
        $maKHList = array_column($responseData, 'MaKH');

        $modifiedMaKHList = array_map(function ($maKH) {
            return substr($maKH, 2);
        }, $maKHList);
        // return $modifiedMaKHList; //17367
        $arrData = [];
        $existMembers = Member::whereIn('MaKH', $modifiedMaKHList)->get();
        
       
       
        foreach ($existMembers as $member) {
            $maKH = $member->MaKH;
            $index = array_search($maKH, $modifiedMaKHList);
            if ($index !== false) {
                $item = $responseData[$index];
               
                $member->MaKH=$item['MaKH'];
                $member->MaCC=$item['MaCC'];
                $member->DiaChi=$item['DiaChi'];
                $member->TenKH=$item['TenKH'];
                $member->MST=$item['MST'];
                $member->Dienthoai=$item['Dienthoai'];
                $member->NguoiLienHe=$item['NguoiLienHe'];
                $member->Email=$item['Email'];
                $member->DiaChiHoaDon=$item['DiaChiHoaDon'];
                $member->DiaChiGiaoHang=$item['DiaChiGiaoHang'];
                $member->save();
                //MaKH MaCC DiaChi  TenKH
                //MST Dienthoai NguoiLienHe Email DiaChiHoaDon DiaChiGiaoHang
                // return $member->MaKH;
            }
        }
        return response()->json([
            'status' => true,
        ]);

        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
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
        //
    }

    public function reportOrderNext(Request $request){
      
        $idAdmin = Auth::guard('admin')->user()->adminid;
       
        $AdminDepartment = Admin::find($idAdmin);
        
        $day=isset($request->day) ? $request->day : Carbon::now()->day;
        $month=isset($request->month) ? $request->month : Carbon::now()->month;
        $year=isset($request->year) ? $request->year :Carbon::now()->year;

        $fromDate =  isset($request['fromDate']) ?$request['fromDate'] : null;
        $toDate =  isset($request['toDate']) ? $request['toDate'] :null;
        if($fromDate=="Invalid date"){
            $fromDate=null;
        }
        if($toDate=="Invalid date"){
            $toDate=null;
        }
      

        $dataFromToDay=[
            'fromDate'=>$fromDate,
            'toDate'=>$toDate,
            'list'=>[],
            'count'=>0,
            'total'=>0
        ];
        $orderListDay=[
            'list'=>[],
            'total'=>0,
            'count'=>0,
            'day'=>$day
        ];
        $orderListMonth=[
            'list'=>[],
            'total'=>0,
            'count'=>0,
            'month'=>$month
        ];
        $orderListYear=[
            'list'=>[],
            'total'=>0,
            'count'=>0,
            'year'=>$year
        ];

        //khách hàng nào chưa phát sinh đơn hàng trong 1 tháng qua (top 10 , và ramdom ngẫu nhiên khách hàng theo tháng )

        $thirtyDaysAgo = Carbon::now()->subDays(30)->toDateTimeString();
        $now = Carbon::now()->toDateTimeString();
       
       
        
        // $query->whereDate($fieldName,'>=',$fromDate)->whereDate($fieldName,'<=',$todate);
        $orderOneMonth=OrderSum::with('member')->whereDate('created_at','<',$thirtyDaysAgo)
        ->where('mem_id','>',0)
        ->orderBy('created_at','desc')->get();

        

        //top 3 sales bán hàng doanh số cao nhất

        $adminSale=Admin::orderBy('adminid','desc')->with('member')->get();
        
        foreach($adminSale as $index=> $admin){
           
            if(count($admin->member)!=0){
               $total=0;
               $count=0;
                foreach($admin->member as $item){
                    $dataOrder= OrderSum::with('orderStatus')->where('mem_id',$item->mem_id)->get();
                        foreach($dataOrder as  $item){
                            $total+=$item['total_price'];
                            $count++;
                        }
                }
                $orderSale[]=[
                    'adminId'=>$admin->adminid,
                    'adminName'=>$admin->username,
                    'total'=>$total,
                    'count'=>$count
                ];
               
            }
        }
        usort($orderSale, fn($a, $b) => strcmp($b['total'], $a['total']));
        $orderBestSale=array_slice($orderSale, 0, 3);

        // mặc hàng nào khách hàng quan tâm nhiều nhất (3 cái )

        $queryProduct=StatisticsPages::where('module','product')
         ->select('uri',DB::raw('SUM(count) as count'),'module')
         ->groupBy('uri','module');
       

        $StatisticsProduct=$queryProduct->orderBy('count','desc')->take(3)->get();
       
        $urlProduct='';
        $bestProduct=[];
       
        foreach( $StatisticsProduct as $item){
           
            if(strlen(strstr($item->uri, "/product-detail/")) > 0){
               
                $urlProduct=str_replace('/product-detail/','',$item->uri);
            }
            $bestProduct[]=ProductDesc::with('product')
            ->where('friendly_url',$urlProduct)->first();
            
        }
       
       
        //danh mục nào khách hàng quan tâm nhiều nhất (3 cái)

        


        $StatisticsCateDesc=StatisticsPages::where('module','category')
        ->select('uri',DB::raw('SUM(count) as count'),'module')
        ->groupBy('uri','module')
        ->orderBy('count','desc')->take(3)->get();
       
        $urlCateDesc='';
        $highCategory=[];
       
        foreach($StatisticsCateDesc as $item){
            $urlCateDesc=$item->uri;
            $highCategory[]=CategoryDesc::with('category')
            ->where('friendly_url',$urlCateDesc)->first();
        }
      

        //danh mục nào khách hàng ít quan tâm nhất

        $StatisticsCateAsc=StatisticsPages::where('module','category')
        ->select('uri',DB::raw('SUM(count) as count'),'module')
        ->groupBy('uri','module')->orderBy('count','asc')
        ->take(3)->get();
       
        $urlCateAsc='';
        $lowCategory=[];
       
        foreach($StatisticsCateAsc as $item){
            $urlCateAsc=$item->uri;
            $lowCategory[]=CategoryDesc::with('category')
            ->where('friendly_url', $urlCateAsc)->first();
        }
        //thông kê truy cập theo ngày theo tuần theo tháng
        $statisCheck=[
            'statisCheckDay'=>'',
            'statisCheckMonth'=>'',
            'statisCheckWeek'=>'',
        ];

        //day
        $dateToCheck = Carbon::now()->toDateString();
        $statisCheckDay=StatisticsPages::where('module','product')
        ->where('date',$dateToCheck)->get();

        //month
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); 
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        $statisCheckMonth=StatisticsPages::where('module','product')
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->get();

        //week
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d'); 
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d'); 

        $statisCheckWeek=StatisticsPages::where('module','product')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->get();
        $statisCheck=[
            'statisCheckDay'=>$statisCheckDay,
            'statisCheckWeek'=>$statisCheckWeek,
            'statisCheckMonth'=>$statisCheckMonth,
        ];

        //check order day,,month,year
       
        if($AdminDepartment->status != 2)
        {
           
            $listOrder = OrderSum::with('orderStatus')
            ->orderBy('created_at','desc')
            ->get();
           
            foreach($listOrder as $index=> $item){
                if(($timestamp = strtotime($item->date_order)) !== false){
                    $value=Carbon::parse($item->date_order)->format('m/d/Y');
                    $listOrder[$index]['date_order']=$value;
                }
                else
                {
                    $listOrder[$index]['date_order']=date("m/d/Y",$item->date_order);
                }
                $listOrder[$index]['day']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('d');
                $listOrder[$index]['month']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('m');
                $listOrder[$index]['year']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('Y');
            }
           
         

            if(isset($fromDate) && isset($toDate)){
                foreach($listOrder as $value){
                    if(strtotime($value['date_order'])>=strtotime($fromDate) && strtotime($value['date_order'])<=strtotime($toDate)){
                        $dataFromToDay['list'][]=$value;
                        $dataFromToDay['total']+=$value['total_price'];
                        $dataFromToDay['count']++;

                    }
                }
            }
            
            foreach($listOrder as $index=>$item){
               
                if($item->year==$year){
                    $orderListYear['list'][]=$item;
                    $orderListYear['total']+=$item->total_price;
                    $orderListYear['count']++;
                    if($item->month==$month){
                        $orderListMonth['list'][]=$item;
                        $orderListMonth['total']+=$item->total_price;
                        $orderListMonth['count']++;
                        if($item->day==$day){
                            $orderListDay['list'][]=$item;
                            $orderListDay['total']+=$item->total_price;
                            $orderListDay['count']++;
                        }
                    }
                }
            }
           
            return response()->json([
                'status'=>true,
                'day'=>$day,
                'month'=>$month,
                'year'=>$year,
                'orderListMonth'=> $orderListMonth,
                'orderListDay'=> $orderListDay,
                'orderListYear'=> $orderListYear,
                'dataFromToDay'=>$dataFromToDay,
                'orderBestSale'=>$orderBestSale,
                'bestProduct'=>$bestProduct,
                'highCategory'=>$highCategory,
                'lowCategory'=>$lowCategory,
                'statisCheck'=>$statisCheck
            ]);

        }else{
            $member = Member::select('mem_id')->where('company','=',Auth::guard('admin')->user()->adminid)->get();


           
            if(count($member)>0)
            {
                $listOrder=[];
                foreach ($member as $value) {
                    if(!isset($search['data']) && !isset($search['status']) &&  !isset($search['fromDate']) && !isset($search['toDate']))
                    {
                        $dataOrder= OrderSum::with('orderStatus')->where('mem_id',$value->mem_id)->get();
                        foreach($dataOrder as $item){
                            $listOrder[]=$item;
                        }
                    }
                }
               
                foreach($listOrder as $index=> $item){
                    if(($timestamp = strtotime($item->date_order)) !== false){
                        $value=Carbon::parse($item->date_order)->format('m/d/Y');
                        $listOrder[$index]['date_order']=$value;
                        $listOrder[$index]['day']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('d');
                        $listOrder[$index]['month']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('m');
                        $listOrder[$index]['year']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('Y');
                    }
                    else
                    {
                        $listOrder[$index]['date_order']=date("m/d/Y",$item->date_order);
                        $listOrder[$index]['day']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('d');
                        $listOrder[$index]['month']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('m');
                        $listOrder[$index]['year']=Carbon::createFromFormat('m/d/Y',$listOrder[$index]['date_order'])->format('Y'); 
                    }
                }
                
            }
           
            
            if(isset($fromDate) && isset($toDate)){
                foreach($listOrder as $value){
                    if(strtotime($value['date_order'])>=strtotime($fromDate) && strtotime($value['date_order'])<=strtotime($toDate)){
                        $dataFromToDay['list'][]=$value;
                        $dataFromToDay['total']+=$value['total_price'];
                        $dataFromToDay['count']++;
                    }
                }
            }
           
          
            foreach( $listOrder as $index=> $item){
                if($item->year==$year){
                    $orderListYear['list'][]=$item;
                    $orderListYear['total']+=$item->total_price;
                    $orderListYear['count']++;
                    if($item->month==$month){
                        $orderListMonth['list'][]=$item;
                        $orderListMonth['total']+=$item->total_price;
                        $orderListMonth['count']++;
                        if($item->day==$day){
                            $orderListDay['list'][]=$item;
                            $orderListDay['total']+=$item->total_price;
                            $orderListDay['count']++;
                        }
                    }
                }
            }
            
           
           
            return response()->json([
                'status'=>true,
                'day'=>$day,
                'month'=>$month,
                'year'=>$year,
                'orderListMonth'=> $orderListMonth,
                'orderListDay'=> $orderListDay,
                'orderListYear'=> $orderListYear,
                'dataFromToDay'=>$dataFromToDay,
                'orderBestSale'=>$orderBestSale,
                'bestProduct'=>$bestProduct,
                'highCategory'=>$highCategory,
                'lowCategory'=>$lowCategory,
                'statisCheck'=>$statisCheck
            ]);
        }
       
    }
}

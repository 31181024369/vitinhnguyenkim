<?php

namespace App\Http\Controllers\API\Admin;

use DateTime;
use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Brand;
use App\Models\BrandDesc;
use App\Models\Coupon;
use App\Models\Member;
use App\Models\Product;
use App\Models\Category;
use App\Models\MemGroup;
use App\Models\CouponDes;
use App\Models\CouponDesUsing;
use App\Models\CategoryDesc;
use App\Rules\CouponRequest;
use Illuminate\Http\Request;
use App\Events\NotificationEvent;
use App\Events\PromotionPusher;
use App\Observers\CouponObserver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponDesRequest;
use App\Notifications\TestNotification;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;
use App\Models\CouponWholeSaleCustomerName;
use App\Http\Controllers\API\Admin\SendNotificationController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\CouponExport;
use GuzzleHttp\Client;
class CouponController extends Controller
{

    public function __construct()
    {
        Coupon::observe(CouponObserver::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Input:searchRole,StartCouponDate,EndCouponDate,nameCoupon
     * Output: Result of Role search
     */
    // public function searchCoupon(Request $request)
    // {
    //     $countCoupon = Coupon::count();
    //     $data = $request->all();
    //     if(isset($data))
    //     {
    //         $resultDateCoupon = Coupon::where('StartCouponDate','>=',isset($data['StartCouponDate']))
    //                                 ->where('EndCouponDate','<=',isset($data['EndCouponDate']))
    //                                 ->get();
    //         $itemCollection = collect(Coupon::all());
    //         $search = $request->TenCoupon;
    //         $filtered = $itemCollection->where('TenCoupon', $search);
    //         $filtered->all();
    //         return response()->json([
    //             'resultDateCoupon' => $resultDateCoupon,
    //             'filtered' => $filtered,
    //             'countCoupon' => $countCoupon,
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status' => false,
    //         ]);
    //     }
    // }
    public function index(Request $request)
    {
        $dataSearch = $request->input('data');
        $offset = $request->page ? $request->page : 1 ;
       
        $coupon=Coupon::with('couponDesc','couponStatus','couponBrand.BrandDesc','couponCategory.categoryDesc')
        ->orderBy('id','DESC');
       
        if($request->data == 'undefined' || $dataSearch =="")
        {
            $list = $coupon;
        }

        else{
            //return response()->json($dataSearch);
            $list = $coupon->where('MaPhatHanh', 'like', '%' . $dataSearch . '%');
        }
        if(isset($request->date)){
           
            $list = $coupon->where('StartCouponDate', '<=',  strtotime($request->date))
            ->where('EndCouponDate', '>=',  strtotime($request->date));
           
        }
        $countCoupon=count($list->get());
        $listCoupon=$list->limit(10)
        ->offset(($offset-1)*10)->get();
       
        foreach($listCoupon as $coupon)
        {
            $memId = $coupon->mem_id;
            $members=[];
            if(is_string($memId)){
                $id = explode(',',$memId);
                $members = Member::whereIn('mem_id',$id)->get();
            }else{
                $members = Member::where('mem_id',$memId)->get(); 
            }
            $isMemIdContained = false;
            foreach ($members as $member) {
                if ($member->mem_id == $memId) {
                    $isMemIdContained = true;
                    break;
                }
            }
            $data[] = [
                'id' => $coupon->id,
                'couponName' => $coupon->TenCoupon,
                'MaPhatHanh' => $coupon->MaPhatHanh,
                'StartCouponDate' => $coupon->StartCouponDate,
                'EndCouponDate' => $coupon->EndCouponDate,
                'DesCoupon' => $coupon->DesCoupon,
                'GiaTriCoupon' => $coupon->GiaTriCoupon,
                'MaxValueCoupon' => $coupon->MaxValueCoupon,
                'SoLanSuDung' => $coupon->SoLanSuDung,
                'KHSuDungToiDa' => $coupon->KHSuDungToiDa,
                'SuDungDongThoi' => $coupon->SuDungDongThoi,
                'DonHangChapNhanTu' => $coupon->DonHangChapNhanTu,
                'DanhMucSpChoPhep' => $coupon->DanhMucSpChoPhep,
                'ThuongHieuSPApDung' => $coupon->ThuongHieuSPApDung,
                'LoaiKHSuDung' => $coupon->LoaiKHSuDung,
                'mem_id' => $coupon->mem_id,
                'DateCreateCoupon' => $coupon->DateCreateCoupon,
                'MaKhoSPApdung' => $coupon->MaKhoSPApdung,
                'couponDescription' => $coupon->coupon_desc,
                'title' => $coupon->couponStatus->title ?? null,
                'colorStatus' => $coupon->couponStatus->color ?? null,
                'couponBrand' => $coupon->couponBrand->brandDesc->title??null,
                'couponCategory' => $coupon->category->categoryDesc->cat_name??null,
                'isMemIdContained' => $isMemIdContained,
                'members' => $members->pluck('username')->toArray(),
            ];
        }
        $listMember =  Member::join('mem_group','mem_group.g_id','=','members.mem_group')
                ->select('mem_group.g_name as NameMemberGroup',
                'members.username','members.mem_id')->get();
        $listProduct = Product::with('productDesc')->where('display',1)->get();
        $listCategory = Category::with('categoryDesc')->where('display',1)->get();
        $listBrand = Brand::with('brandDesc')->where('display',1)->get();
        return response()->json([
            'listCoupon' => $data,
            'countCoupon'=>$countCoupon
            // 'listMember' => $listMember,
            // 'listProduct' => $listProduct,
            // 'listBrand' => $listBrand,
            // 'listCategory' => $listCategory,
        ]);
    }
    public function export(){
        $fileName = 'coupon_'.date('Y_m_d_H_i_s').'.xlsx';
        $export = (new CouponExport);

        $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];
        return response($fileContents, 200, $headers);
    }

    public function create()
    {
        $listCoupon = Coupon::join('coupon_status','coupon_status.status_id','=','coupon.status_id')
                              ->join('product_brand_desc','product_brand_desc.brand_id','=','coupon.ThuongHieuSPApDung')
                              ->join('product_category_desc','product_category_desc.cat_id','=','coupon.DanhMucSpChoPhep')
                              ->select('*','coupon_status.title as Status','product_brand_desc.title as ThuongHieu',
                                        'product_category_desc.cat_name as Category')
                            ->paginate(15);
        $listMember =  Member::join('mem_group','mem_group.g_id','=','members.mem_group')
                                ->select('mem_group.g_name as NameMemberGroup',
                                'members.username','members.mem_id')->get();
        $listProduct = Product::paginate(10);
        $listCategory = Category::paginate(10);
        $listBrand = Brand::paginate(10);
        $listCategoryDesc = CategoryDesc::paginate(10);
        return response()->json([
            'listCoupon' => $listCoupon,
            'listMember' => $listMember,
            'listProduct' => $listProduct,
            'listBrand' => $listBrand,
            'listCategory' => $listCategory,
            'listCategoryDesc' => $listCategoryDesc,
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
            
            $validator = CouponRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ]);
            }
            //checkDb
            $check = Coupon::where('TenCoupon',$request->TenCoupon)->orWhere('MaPhatHanh',$request->MaPhatHanh)->first();
            if($check != '' && $check->TenCoupon == $request->TenCoupon)
            {
                return response()->json([
                    'message'=>'tencoupon',
                    'status'=>'false'
                ]);
            }
            if($check != '' && $check->MaPhatHanh == $request->MaPhatHanh)
            {
                return response()->json([
                    'message'=>'maphathanh',
                    'status'=>'false'
                ]);
            } 
            
            $now = Carbon::now();
            $date = Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('DD-MM-YYYY hh:mm:ss');
            $coupon = new Coupon();
            $coupon -> TenCoupon = $data['TenCoupon'];
            $coupon -> MaPhatHanh = $data['MaPhatHanh'];
            $coupon -> StartCouponDate = strtotime($data['StartCouponDate']);
            $coupon -> EndCouponDate = strtotime($data['EndCouponDate']);
            $coupon -> DesCoupon = $data['DesCoupon'];
            $coupon -> GiaTriCoupon = $data['GiaTriCoupon'];
            $coupon -> SoLanSuDung = $data['SoLanSuDung'];
            $coupon -> KHSuDungToiDa = $data['KHSuDungToiDa']?$data['KHSuDungToiDa']:0;
            $coupon -> DonHangChapNhanTu = $data['DonHangChapNhanTu']?$data['DonHangChapNhanTu']:0;
            $coupon -> status_all_member = 0;
            if(!is_null($data['mem_id']) )
            {
                $coupon->mem_id = implode(',',$data['mem_id']);
            }else{
                $coupon -> mem_id = 0;
            }
            if(!is_null($data['DanhMucSpChoPhep']) )
            {
                $coupon->DanhMucSpChoPhep = implode(',',$data['DanhMucSpChoPhep']);
            }else{
                $coupon -> DanhMucSpChoPhep = NULL;
            }
            if(!is_null($data['ThuongHieuSPApDung']) )
            {
                $coupon->ThuongHieuSPApDung = implode(',',$data['ThuongHieuSPApDung']);
            }else{
                $coupon -> ThuongHieuSPApDung = NULL;
            }
            $coupon -> LoaiKHSuDUng =1;
            $coupon -> DateCreateCoupon = $date;
            $coupon -> MaKhoSPApdung =  isset($request->MaKhoSPApdung) ? $data['MaKhoSPApdung'] : 0;
            $coupon -> IDAdmin = isset($data['IDAdmin']) ? $data['IDAdmin'] : 0;
            $coupon -> status_id = $data['status_id'];
            $coupon ->CouponType = isset($data['type'])?   $data['type'] :0;
            
            $coupon->save();
            $couponDes ="";
            
            if($coupon ->CouponType == 0) {
            $prefixMaCoupon = isset($data['prefix']) ? $data['prefix'] : '';
            $suffixesMaCoupon = isset($data['suffixes']) ? $data['suffixes'] : '';

            switch ($data['number']) {
                case 1:
                    $result = 9;
                    break;
                case 2:
                    $result = 99;
                    break;
                case 3:
                    $result = 999;
                    break;
                case 4:
                    $result = 9999;
                    break;
                case 5:
                    $result = 99999;
                    break;
                case 6:
                    $result = 999999;
                    break;
                default:
                    exit; 
            }
            
            for($i=0; $i < $data['SoLanSuDung']; $i++)
            {      
                $MaCouponDes = $prefixMaCoupon.''.rand(0,$result).''.$suffixesMaCoupon;
                $couponDes = new CouponDes();
                $couponDes -> MaCouponDes = $MaCouponDes;
                $couponDes -> SoLanSuDungDes = 1;
                $couponDes -> SoLanConLaiDes = $data['SoLanSuDung'];
                $couponDes -> StatusDes = $request->StatusDes ? $data['StatusDes'] : 0;
                $couponDes -> DateCreateDes = $date;
                $couponDes -> idCoupon = $coupon->id;
                $couponDes -> save();
            }
            }
            $selectedMemberIds = $request->input('mem_id');
            $message = $request->input('message');
            $members = Member::whereIn('mem_id',$selectedMemberIds)->get();
            $data =[
                'name' =>  $coupon -> TenCoupon,
                'price'=> $coupon -> GiaTriCoupon,
                'code'=> $coupon -> MaPhatHanh,
                'link'=>'',
                'members'=>isset($members) ? $members :" null",
                'status'=> 1
            ];
            try {
                $message=json_encode($data);
                
                $endpoint = 'http://192.168.245.176:1402/api/notifies';
                $endpoint .= '?message='.$message;
                $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
                $client = new Client();
                $response = $client->request('GET', $endpoint, ['headers' => $headers]);
              
            } catch(Exception $e) {
                return ['error' => $e->getMessage()];
            }
       

       

        //     $options = array(
        //         'cluster' => 'ap1',
        //         'encrypted' => true
        //     );
       
    
        //     $pusher = new Pusher(
        //         env('PUSHER_APP_KEY'),
        //         env('PUSHER_APP_SECRET'),
        //         env('PUSHER_APP_ID'),
        //         $options
        //     );
       
        //  $pusher->trigger('PromotionPusher', 'promotion-channel', $coupon);
        //12/29
            // $selectedMemberIds = $request->input('mem_id');
            // $message = $request->input('message');
            // $members = Member::whereIn('mem_id',$selectedMemberIds)->get();
                             
            return response()->json([
                'coupon' => $coupon,
                'couponDes' => $couponDes,
                'members' => $members,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $listCouponId = Coupon::with('couponDesc')->find($id);
        $listMember = MemGroup::paginate(10);
        $listProduct = Product::paginate(10);
        $listBrand = Brand::paginate(10);
        return response()->json([
            'listCouponId' => $listCouponId,
            'listMember' => $listMember,
            'listProduct' => $listProduct,
            'listBrand' => $listBrand
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
        $couponUsing = CouponDesUsing::with('couponDesUsing')->get();
        $listCoupon = Coupon::with('couponDesc')->find($id);

        
        //member
        $member = explode(",",$listCoupon->mem_id);
        $savemember =[];
        if($listCoupon->mem_id!="")
        {
            foreach ($member as $value) {
                $savemember[] = Member::select('MaKH')->whereRaw('FIND_IN_SET(?, mem_id)', [$value])->first();
            }
        }
        $listCoupon['mem_id'] = $savemember;

        //brand
        $brand = explode(",",$listCoupon->ThuongHieuSPApDung);
        $savebrand =[];
        if($listCoupon->ThuongHieuSPApDung!="")
        {
            foreach ($brand as $value) {
                $savebrand[] = BrandDesc::select('title')->whereRaw('FIND_IN_SET(?, brand_id)', [$value])->first();
            }
        }
        $listCoupon['ThuongHieuSPApDung'] = $savebrand;

        //category 
        $category = explode(",",$listCoupon->DanhMucSpChoPhep);
        $savecategory =[];
        if($listCoupon->DanhMucSpChoPhep!="")
        {
            foreach ($category as $value) {
                $savecategory[] = CategoryDesc::select('cat_name')->whereRaw('FIND_IN_SET(?, cat_id)', [$value])->first();
            }
        }
        $listCoupon['DanhMucSpChoPhep'] = $savecategory;

        $CouponId = DB::table('coupon','coupon.couponDesc')->where('id',$id)->first();
       
       $listCoupon['couponUsing']= [$couponUsing];
        return response()->json([
            'listCoupon' => $listCoupon,
            'CouponId' => $CouponId,
           
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
        // try{
        //     $data = $request->all();
        //     $validator = CouponRequest::validate($request->all());
        //     if($validator->fails()){
        //         return response()->json([
        //             'message'=>'Validations fails',
        //             'errors'=>$validator->errors()
        //         ],422);
        //     }
        //     $coupon =  Coupon::find($id);
        //     $coupon -> TenCoupon = $data['TenCoupon'];
        //     $coupon -> MaPhatHanh = $data['MaPhatHanh'];
        //     $coupon -> StartCouponDate = $data['StartCouponDate'];
        //     $coupon -> DesCoupon = $data['DesCoupon'];
        //     $coupon -> GiaTriCoupon = $data['GiaTriCoupon'];
        //     $coupon -> SoLanSuDung = $data['SoLanSuDung'];
        //     $coupon -> KHSuDungToiDa = $data['KHSuDungToiDa'];
        //     $coupon -> DonHangChapNhanTu = $data['DonHangChapNhanTu'];
        //     $coupon -> DanhMucSpChoPhep = $data['DanhMucSpChoPhep'];
        //     $coupon -> ThuongHieuSPApDung = $data['ThuongHieuSPApDung'];
        //     $coupon -> LoaiKHSuDUng = $data['LoaiKHSuDUng'];
        //     $coupon -> DateCreateCoupon = $data['DateCreateCoupon'];
        //     $coupon -> MaKhoSPApdung = $data['MaKhoSPApdung'];
        //     $coupon -> IDAdmin = isset($data['IDAdmin']) ? $data['IDAdmin'] : '';
        //     $coupon -> status_id = $data['status_id'];
        //     $coupon -> save();
            
        //     return response()->json([
        //         'coupon' => $coupon,
        //         'status' => true
        //     ]);
        // }catch(\Throwable $th){
        //     return response()->json([
        //       'status' => false,
        //       'message' => $th->getMessage()
        //     ]);
        // }
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

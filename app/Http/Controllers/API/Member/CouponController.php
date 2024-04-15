<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\BrandDesc;
use App\Models\CategoryDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * input : token member
     * output : coupon memberow
     * describe : check token member and show data coupon member
     */
    public function index()
    {
        try{        
            $now = date('Y-m-d H:i:s');
            $stringTime = strtotime($now);
            if(auth('member')->user() == null ) {
                return response()->json([
                    'status'=> "false",
                    'message' => "not login"
                ]);
            }
            $dataForYou =[];
            $dataPublic =[];
            
            if(auth('member')->user()->mem_id ) {
                $listCouponForYou = Coupon::with('couponDesc')->orderBy('id','DESC')
                                ->whereRaw('FIND_IN_SET(?,mem_id)',[auth('member')->user()->mem_id])
                                ->where('StartCouponDate','<=',$stringTime)
                                ->where('EndCouponDate','>=',$stringTime)
                                ->get();
        
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
        }
    }
    catch(Exception $e){
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }      
    }
    
}
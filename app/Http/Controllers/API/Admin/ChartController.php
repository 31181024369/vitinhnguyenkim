<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\StatisticsPages;
use Illuminate\Http\Request;
use App\Rules\CategoryValidate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\Admin\AbstractController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ChartController extends Controller
{
    public function index(Request $request)
    {
        $date = getdate();
        $day = $date['mday'];
        $mon = $date['mon'];
        $year = $date['year'];
       
        $data = StatisticsPages::get();
        //year
        $one = 0;
        $two= 0;
        $three = 0;
        $four = 0;
        $five = 0;
        $six =0;
        $seven =0;
        $eight = 0;
        $nine =0;
        $ten = 0;
        $eleven = 0;
        $twelve= 0;
        $thirteen = 0;
        $fourteen = 0;
        $fifteen = 0;
        $sixteen =0;
        $seventeen =0;
        $eighteen = 0;
        $nineteen =0;
        $twenty = 0;
        $twentyOne =0;
        $twentyTwo = 0;
        $twentyThree = 0;
        $twentyFour= 0;
        $twentyFive = 0;
        $twentySix = 0;
        $twentySeven = 0;
        $twentyEight =0;
        $twentyNine = 0;
        $thirty = 0;
        $thirtyOne= 0;
       
        //mon
        foreach ($data as $value) {
            
            $dateDb=date_create($value->date);
            $yearDb = date_format($dateDb,"Y");
            $monDb = date_format($dateDb,"m");
            $dayDb = date_format($dateDb,"d");
            if($request->status == "Năm" && $yearDb == $year)
            {
                switch ($monDb) {
                    case 1:
                        $one = $one + $value->count;
                        break;
                    case 2:
                        $two = $two + $value->count;
                        break;
                    case 3:
                        $three = $three + $value->count;
                        break;
                    case 4:
                        $four = $four + $value->count;
                        break;
                    case 5:
                        $five = $five + $value->count;
                        break;
                    case 6:
                        $six = $six + $value->count;
                        break;
                    case 7:
                        $seven = $seven + $value->count;
                        break;
                    case 8:
                        $eight = $eight + $value->count;
                        break;
                    case 9:
                        $nine = $nine + $value->count;
                        break;
                    case 10:
                        $ten = $ten + $value->count;
                        break;
                    case 11:
                        $eleven = $eleven + $value->count;
                        break;
                    case 12:
                        $twelve = $twelve + $value->count;
                        break;
                    
                  }
                  $listData= [$one,$two,$three,$four,$five,$six,$seven,$eight,$nine,$ten,$eleven,$twelve];
            }
            if($request->status == "Tháng" && $yearDb == $year && $monDb == $mon )
            {
                switch ($dayDb) {
                    case 1:
                        $mot = $mot + $value->count;
                        break;
                    case 2:
                        $hai = $hai + $value->count;
                        break;
                    case 3:
                        $ba = $ba + $value->count;
                        break;
                    case 4:
                        $bon = $bon + $value->count;
                        break;
                    case 5:
                        $nam = $nam + $value->count;
                        break;
                    case 6:
                        $sau = $sau + $value->count;
                        break;
                    case 7:
                        $bay = $bay + $value->count;
                        break;
                    case 8:
                        $tam = $tam + $value->count;
                        break;
                    case 9:
                        $chin = $chin + $value->count;
                        break;
                    case 10:
                        $muoi = $muoi + $value->count;
                        break;
                    case 11:
                        $muoimot = $muoimot + $value->count;
                        break;
                    case 12:
                        $muoihai = $muoihai + $value->count;
                        break;
                    case 13:
                        $muoiba = $muoiba + $value->count;
                        break;
                    case 14:
                        $muoibon = $muoibon + $value->count;
                        break;
                    case 15:
                        $ba = $ba + $value->count;
                        break;
                    case 16:
                        $bon = $bon + $value->count;
                        break;
                    case 17:
                        $nam = $nam + $value->count;
                        break;
                    case 18:
                        $sau = $sau + $value->count;
                        break;
                    case 19:
                        $bay = $bay + $value->count;
                        break;
                    case 20:
                        $tam = $tam + $value->count;
                        break;
                    case 21:
                        $chin = $chin + $value->count;
                        break;
                    case 22:
                        $muoi = $muoi + $value->count;
                        break;
                    case 23:
                        $muoimot = $muoimot + $value->count;
                        break;
                    case 24:
                        $muoihai = $muoihai + $value->count;
                        break;
                    case 25:
                        $mot = $mot + $value->count;
                        break;
                    case 26:
                        $hai = $hai + $value->count;
                        break;
                    case 27:
                        $ba = $ba + $value->count;
                        break;
                    case 28:
                        $bon = $bon + $value->count;
                        break;
                    case 29:
                        $nam = $nam + $value->count;
                        break;
                    case 30:
                        $sau = $sau + $value->count;
                        break;
                    case 31:
                        $manot = $bay + $value->count;
                        break;
                  }
            }
            
        }
        
        return response()->json([
            'status'=> true,
            'data'=> $listData
        ]);
    }

    public function edit($id)
    {
        }
}
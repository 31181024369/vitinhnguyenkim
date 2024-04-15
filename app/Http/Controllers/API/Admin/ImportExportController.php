<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Product;
use App\Models\ProductDesc;
use App\Models\OrderSum;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\productSearchExport;
use Illuminate\Support\Facades\DB;
use App\Exports\productTechnologyExport;
use App\Imports\productTechnologyImport;
use App\Models\BrandDesc;
use App\Models\Brand;
use App\Exports\brandExport;

use App\Models\ProductPerties;
use App\Models\PropertiesCategory;
use App\Models\Price;
use App\Models\Properties;
use App\Models\ProductProperties;
use App\Imports\codeVoucherImport;
use App\Models\codeVoucherProduct;
use Illuminate\Support\Str;

class ImportExportController extends Controller
{
    public function import(Request $request)
    {
        $cat_id = $request->cat_id;

        Excel::import(new ProductsImport($cat_id), $request->file('file'));
        return response()->json([
            "status" => "true",
        ]);
    }
   
    public function importTechnologyExcel(Request $request)
    {
   
        try {
            $files = $request->file('file');
            Excel::import(new productTechnologyImport, $files);
    
            return response()->json([
                'status' => 'true',
                'message' => 'Excel file imported successfully.',
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
    public function exportCodevoucher(Request $request){
      
        try{
            // $files = $request->file('file');
           
            // $import = new codeVoucherImport();
            // $import->ignoreFirstRow();
        
            // Excel::import($import, $files);
        
            // $data = $import->getDataByRows();
          
          
            // foreach ($data as $key=> $row) {
            //     if($key!=0){
            //         foreach($row as $item){
                      
            //             if($item!=null){
            //                 codeVoucherProduct::create([
            //                     'code' => $item, 
            //                 ]);
                            
            //             }
            //         }
                    

            //     }
                
            // }
            $files = $request->file('file');

            $import = new codeVoucherImport();
            
            $data = Excel::toArray($import, $files);
            
            $rows = array_slice($data[0], 1); 
            
            foreach ($rows as $row) {
                $column1 = $row[15]; 
                if ($column1 !== null) {
                    codeVoucherProduct::create([
                        'code' => $column1,
                    ]);
                }
            }


            return response()->json([
                'status'=>true
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
    public function getTechnology($id){
        $price=Price::where('product_id', $id)->where('main',1)->first();
        $dataOp=[];
        if(isset($price))
        {
            $propertiesProduct=ProductProperties::with('properties','propertiesValue')->where('price_id',$price->id)->get();
            foreach($propertiesProduct as $value){
                array_push($dataOp, [
                    'catOption' => isset($value->properties) ? $value->properties->title : '',
                    'nameCatOption' => isset($value->description) ? $value->description : ''
                ]);
            }
        }
        return $dataOp;
    }
    
    public function exportTechnologyExcel(Request $request){
       
        // $categoryId=$request['categoryId'];
        // $brandId=$request['brandId'];
        // $categoryId=1;
        // $brandId=5;
       
        $dataKey = json_decode($request['key']);
       
       
        $value=null;
        foreach($dataKey as $item){
            $value=[
                'brandId'=>$item->brandId,
                'categoryId'=>$item->categoryId,
            ];
        }
       
        $brandId=$value['brandId'];
        $categoryId=$value['categoryId'];
        
        $product=Product::with('productDesc','categoryDes')
        ->where(function ($query) use ($categoryId, $brandId) {
            $query->whereRaw('FIND_IN_SET(?, cat_list)', [$categoryId]);
        });
        if($brandId != 0)
        {
            $product= $product->where('brand_id',$brandId)->get();
        }
        else{
            $product=$product->get();
        }
        
       
        // ->whereRaw('FIND_IN_SET(?, cat_list)', [$categoryId])->take(10)->get();
       
              
        foreach($product as $key=>$value)
        {

            $dataValue=$this->getTechnology($value->product_id);
            $product[$key]['technology'] = $dataValue;
            
        }
        $data=[];
        $listTech=[];
        
        foreach($product as $key => $item){
          
            foreach ($item['technology'] as $key => $value) {
                if(!in_array($value['catOption'], $listTech)){
                    $listTech[]=$value['catOption'];
                }
            }
            $data['infoProduct'][]=[
                "makho"=>isset($item['macn'])?$item['macn']: '',
                "tensanpham"=>isset($item->productDesc) ?$item->productDesc->title: '',
                'catOp'=>''
            ];
        }
        $data['listTech']=$listTech;
        
        foreach($product as $key => $item){
            $catOp=[];
            foreach($listTech as $key1=>$item1){
                foreach ($item['technology'] as $key2 => $value2) {
                    if($item1==$value2['catOption']){
                        // $catOp[]=$value2['nameCatOption'];
                        array_push($catOp,[
                            'catOption'=> $value2['catOption'],
                            'nameCatOption' => $value2['nameCatOption']]);
                        }
                    }
                }
            $data['infoProduct'][$key]['catOp']=$catOp;
        }

       
        $fileName = 'productListTechnology_' . date('Y_m_d_H_i_s') . '.xlsx';
        $export = new productTechnologyExport($data);
        $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];
        return response($fileContents, 200, $headers);
    }
    public function exportBrand(){
        $data=BrandDesc::with('brand')->orderBy('brand_id','asc')->get();
        $fileName = 'productSearch_' . date('Y_m_d_H_i_s') . '.xlsx';
        $export = new brandExport($data);
        $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];
        return response($fileContents, 200, $headers);
        
    }
    public function exportExcel(Request $request)
    {
        //return $request['key'];
        $dataKey = json_decode($request['key']);
     
        $value=null;
        foreach($dataKey as $item){
            $value=[
                'brandId'=>$item->brandId,
                'categoryId'=>$item->categoryId,
            ];
        }
       
        //17   373
        $brandId=$value['brandId'];
        $categoryId=$value['categoryId'];
       
        $product=Product::with('productDesc','productPicture','categoryDes','brandDesc')
        ->where(function ($query) use ($categoryId, $brandId) {
            $query->whereRaw('FIND_IN_SET(?, cat_list)', [$categoryId]);
        });
       
        if($brandId != 0)
        {
            $product= $product->where('brand_id',$brandId)->get();
        }
        else{
            $product=$product->get();
        }
       
       
        $data=[];
       foreach($product as $item){
       
            $data[]=[
                "product_id"=>$item['product_id'],
                "cat_name"=>$item['categoryDes']['cat_name'],
                "maso"=>$item['maso'],
                "macn"=>$item['macn'],
                "price"=>$item['price'],
                "price_old"=>$item['price_old'],
                "brand_name"=>$item['brandDesc']['title'],
                "title"=>$item['productDesc']['title'],
                "picture"=>count($item['productPicture'])==0 ? " không có hình" :"có " .count($item['productPicture'])." hình",
                "static"=>$item['stock']==1 ? "còn hàng":"hết còn hàng",
                'technology'=>count($this->getTechnology($item['product_id']))>0 ? "có":"không",
                'describe'=>isset($item->productDesc) ? Str::length($item->productDesc->description)>300 ? "có" : "quá ngắn" : "không"

            ];
       }
     
       
        $fileName = 'productSearch_' . date('Y_m_d_H_i_s') . '.xlsx';
        $export = new productSearchExport($data);
        $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Security-Policy'=> "upgrade-insecure-requests" ,
        ];
        return response($fileContents, 200, $headers);
    
    }
}

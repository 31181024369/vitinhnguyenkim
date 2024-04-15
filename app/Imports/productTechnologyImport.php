<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Price;
use App\Models\ProductProperties;
use App\Models\PropertiesCategory;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Imports\YourImportClass;


class productTechnologyImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        $collection = $collection->toArray();
        $listTech=[];
        foreach($collection as $key => $row){
            if($key==0){
               for($i=2;$i<count($row);$i++){
                $listTech[]=$row[$i];
               }
            }
            if($key>=1){
                $listValue=[];
               for($i=2;$i<count($row);$i++){
                $listValue[]=$row[$i];
               }
              
            //    $list=array_combine($listTech, $listValue);
               //dd( $list);
              
                $product = Product::where('macn',$row[0])->first();
                

        
                if($product != null)
                {
                    $cat_id = explode(",",$product->cat_list);
                    
                    $category = PropertiesCategory::with('properties.propertiesValue')->where('cat_id',$cat_id[0])->get();
                   
                    $listPrice=Price::where('product_id',$product->product_id)->get();
                    foreach($listPrice as $value){
                        ProductProperties::where('price_id',$value->id)->delete();
                    }
                    Price::where('product_id',$product->product_id)->delete();
                  
                    $price = new Price();
                    $price->cat_id = $product->cat_id;
                    $price->product_id = $product->product_id;
                    $price->price = $product->price;
                    $price->price_old = $product->price_old;
                    $price->picture = $product->picture;
                    $price->main = 1;
                    // $price->technology=json_encode($list);
                    $price ->save();
                    
            
                    foreach ($category as $ky => $item) {
                        foreach ($listTech as $key => $value) {
                            if($value == $item->properties->title)
                            {
                                $productPerties = new ProductProperties();
                                $productPerties -> pv_id = 1;
                                foreach ($item->properties->propertiesValue as $ke => $row) {
                                    if($listValue[$key] == $row->name)
                                    {
                                        $productPerties -> pv_id = $row->id;
                                    }
                                }
                                $productPerties -> properties_id = $item->properties->id;
                                $productPerties -> price_id = $price->id;
                                $productPerties -> description = $listValue[$key];
                                $productPerties->save();
                                
                            }
                        }
                        
                    }
                    
                }
            }
        }
    }
    
}

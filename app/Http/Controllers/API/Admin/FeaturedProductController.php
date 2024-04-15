<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FeaturedProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    $listFeatureProduct = DB::table('product')
                            ->join('product_category_desc','product.cat_id','=','product_category_desc.id')
                            ->join('product_brand_desc','product_brand_desc.id','=','product.brand_id')

                            ->select('product_category_desc.cat_name as product_category_name',
                                    'product_brand_desc.title as product_brand_name',
                                    'product.picture','product.views','product.date_post')
                            ->get();
        return response()->json($listFeatureProduct);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->search;
        $listFeatureProduct = Product::where('name', 'LIKE', '%'.$search.'%')->paginate(15);
        return response()->json($listFeatureProduct);
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

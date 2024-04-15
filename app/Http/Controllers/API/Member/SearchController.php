<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;

class SearchController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true
        ]);
    }
    public function search(Request $request)
    {
        try{


        $searchterm = $request->input('query');

        $searchResults = (new Search())
            ->registerModel(\App\Product::class, ['maso', 'description']) 
            
            ->registerModel(\App\Category::class, function (ModelSearchAspect $modelSearchAspect) {
                $modelSearchAspect
                    ->addExactSearchableAttribute('name') 
                    ->addSearchableAttribute('description'); 
            })
            ->perform($searchterm);

        return response()->json([
            'searchResults' => $searchResults,
            'searchTerm' => $searchTerm
        ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

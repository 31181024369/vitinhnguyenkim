<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adpos;
use App\Models\Advertise;

class AdvertiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
           
            
            $advertise=Advertise::orderBy('menu_order','asc')->get();
           
            $adpos=Adpos::get();

            $data=[];
            foreach($advertise as $item){
                $adpos=Adpos::where('name',$item->pos)->first();
                $data[]=[
                    'id'=>$item->id,
                    'title'=>$item->title,
                    'picture'=>$item->picture,
                    'pos'=>$item->pos,
                    'width'=>$item->width,
                    'height'=>$item->height,
                    'link'=>$item->link,
                    'target'=>$item->target,
                    'module_show'=>$item->module_show,
                    'description'=>$item->description,
                    'menu_order'=>$item->menu_order,
                    'display'=>$item->display,
                    'lang'=>$item->lang,
                    'ad_pos'=>$adpos

                ];
                
            }
            return response()->json([
                'statis'=>true,
                'data'=>$data
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
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

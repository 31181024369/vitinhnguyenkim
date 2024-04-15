<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CouponStatusRequest;
use App\Models\CouponStatus;

class CouponStatusController extends Controller
{
    /**
         * Input: name="search", method="GET", route="order-status-search"
         * Output: list of results
         */
    public function search(Request $request)
    {
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $listCouponStatus = CouponStatus::where('title', 'LIKE', '%'.$search.'%')->get();
            return response()->json($listCouponStatus);
        }else{
            return response()->json([
                'message' => 'Invalid search parameters  provided for this search term.',
                'status' => false
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listCouponStatus = CouponStatus::all();
        return response()->json($listCouponStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listCouponStatus = CouponStatus::all();
        return response()->json($listCouponStatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponStatusRequest $request)
    {
        $data = $request->all();
        $couponStatus = new CouponStatus();
        $couponStatus -> title = $data['title'];
        $couponStatus -> save();
        return response()->json($couponStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $couponStatusId = CouponStatus::find($id);
        return response()->json($couponStatusId);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $couponStatusId = CouponStatus::find($id);
        return response()->json($couponStatusId);
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
        $data = $request->all();
        $couponStatus = CouponStatus::find($id);
        $couponStatus -> title = $data['title'];
        $couponStatus -> save();
        return response()->json($couponStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $couponStatus = CouponStatus::find($id);
        $couponStatus -> delete();
        return response()->json($couponStatus);
    }
}

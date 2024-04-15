<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\StatisticsPages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Admin\AbstractController;
use App\Exports\StatisticsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function getModel()
    {
        return new StatisticsPages();
    }
    
    public function index(Request $request)
    {
        $fromDate =  isset($request['fromDate']) ? Carbon::parse($request['fromDate'])->format('Y-m-d') : null;
        $toDate =  isset($request['toDate']) ? Carbon::parse($request['toDate'])->format('Y-m-d') :null;
        $query = StatisticsPages::with('member');
        if(!isset($fromDate) && !isset($toDate)){
            $listStatistics=$query->paginate(10);
        }
        else if(isset($fromDate) && isset($toDate)){
            $listStatistics=$query->whereBetween('date', [$fromDate, $toDate])->paginate(10);
        }
        else if($request->input('data')=='undefined' && $request->input('data')==''){
            $listStatistics=$query->whereBetween('date', [$fromDate, $toDate])->paginate(10);
        }
        return response()->json([
            'status' => true,
            'data' =>  $listStatistics
        ]);
    }
    public function export(Request $request){
        $id =  $request->input('id') ? $request->input('id') : ''; 
        $fromDate = $request->input('fromDate') ? $request->input('fromDate') : '';
        $toDate = $request->input('toDate') ? $request->input('toDate') : '';
        $fileName = 'statistics_'.date('Y_m_d_H_i_s').'.xlsx';
        $export = (new StatisticsExport($id,$fromDate,$toDate));
        $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];
        return response($fileContents, 200, $headers);
        // Excel::store($export, $fileName, 'public');
        // $fileUrl = Storage::url($fileName);
        // return Excel::download($export, 'statistics.xlsx');

        // return response()->json([
        //             "status" => "true",
        //         ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
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
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }
}
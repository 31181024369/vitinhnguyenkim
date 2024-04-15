<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\ServiceDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
 {
    protected function getModel()
    {
        return new Service();
    }
    public function index(Request $request)
    {
        try {
            if($request->data == 'undefined' || $request->data =="")
            {
                $service = Service::with('serviceDesc')->paginate(10);
            }
            else{
                $service = Service::with('serviceDesc')->whereHas('serviceDesc', function ($query) use ($request) {
                    $query->where("title", 'like', '%' . $request->data . '%');})->paginate(10);
            }
        return response()->json(
            [
            'status' => 'success',
            'list' => $service,
            ]
        );
        } catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];
            return response()->json( $response, 500 );
        }  
    }
    public function store(Request $request)
    {
        $service = new Service();
        $serviceDesc = new ServiceDesc();
        try {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $filePath = $request->file('picture')->storeAs('service',$fileNameToStore);
            $service->fill([
                'picture' => $filePath,
                'views'=> $request->input('views'),
                'display' => $request->input('display'),
                'menu_order' => $request->input('menu_order'),
                'adminid' => $request->input('adminid')
            ])->save();
            $serviceDesc->service_id = $service->service_id;
            $serviceDesc->title = $request->input('title');
            $serviceDesc->description = $request->input('description');
            $serviceDesc->short = $request->input('short');
            $serviceDesc->friendly_url = $request->input('friendly_url');
            $serviceDesc->friendly_title = $request->input('friendly_title');
            $serviceDesc->metakey = $request->input('metakey');
            $serviceDesc->metadesc = $request->input('metadesc');
            $serviceDesc->lang = $request->input('lang');
            $serviceDesc->save();
            $response = [
                'status' => 'success',
                'service' => $service,
                'serviceDesc' => $serviceDesc,
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];
            return response()->json($response, 500);
        }
    }
    public function destroy($id)
    {
        $list = Service::Find($id)->delete();
    }
    public function edit($id)
    {
        $listServiceDesc = Service::with('serviceDesc')->find($id);
          return response()->json([
            'status'=> true,
            'service' => $listServiceDesc
        ]);
    }
    public function update(Request $request, $id)
    {   
        $service = new Service();
        $serviceDesc = new ServiceDesc();
        $listService = Service::Find($id);
        
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $filePath = $request->file('picture')->storeAs('service',$fileNameToStore);
        } else {   
            $filePath = $list->picture; 
        }
        
        $listService->picture = $filePath;
        $listService->views = $request->input('views');
        $listService->display = $request->input('display');
        $listService->menu_order = $request->input('menu_order');
        $listService->adminid = $request->input('adminid');
        $listService->save();

        $serviceDesc = ServiceDesc::where('service_id', $id)->first();
        if ($serviceDesc) {
            $serviceDesc->title = $request->input('title');
            $serviceDesc->description = $request->input('description');
            $serviceDesc->short = $request->input('short');
            $serviceDesc->friendly_url = $request->input('friendly_url');
            $serviceDesc->friendly_title = $request->input('friendly_title');
            $serviceDesc->metakey = $request->input('metakey');
            $serviceDesc->metadesc = $request->input('metadesc');
            $serviceDesc->lang = $request->input('lang');
            $serviceDesc->save();
        }
    }
 }
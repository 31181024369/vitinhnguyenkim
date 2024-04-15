<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\Guide;
use App\Models\GuideDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;

class GuideController extends AbstractController
 {

    protected function getModel()
     {
        return new Guide();
    }

    public function index()
    {
    try {
      
        $guide = Guide::with('guideDesc')->paginate(10);
        $response = [
            'status' => 'success',
            'list' => $guide,

        ];

        return response()->json( $response, 200 );
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
        $guide = new Guide();
        $guideDesc = new GuideDesc();
        try {
        
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
           
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            
            $filePath = $request->file('picture')->storeAs('guide',$fileNameToStore);
          
            $guide->fill([
                'picture' => $filePath,
                'views'=> $request->input('views'),
                'display' => $request->input('display'),
                'menu_order' => $request->input('menu_order'),
                'adminid' => $request->input('adminid')
            ])->save();
            $guideDesc->guide_id = $guide->guide_id;
            $guideDesc->title = $request->input('title');
            $guideDesc->description = $request->input('description');
            $guideDesc->short = $request->input('short');
            $guideDesc->friendly_url = $request->input('friendly_url');
            $guideDesc->friendly_title = $request->input('friendly_title');
            $guideDesc->metakey = $request->input('metakey');
            $guideDesc->metadesc = $request->input('metadesc');
            $guideDesc->lang = $request->input('lang');
            $guideDesc->save();

            $response = [
                'status' => 'success',
                'guide' => $guide,
                'guideDesc' => $guideDesc,
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
        $list = Guide::Find($id)->delete();
    }
    public function edit($id)
    {
        $listGuideDesc = Guide::with('guideDesc')->find($id);
          return response()->json([
            'status'=> true,
            'guide' => $listGuideDesc
        ]);
    }
    public function update(Request $request, $id)
    {   
        $guide = new Guide();
        $guideDesc = new GuideDesc();
        $listGuide = Guide::Find($id);
        
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
           
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            
            $filePath = $request->file('picture')->storeAs('guide',$fileNameToStore);
        } else {   
            $filePath = $list->picture; 
        }
        
        $listGuide->picture = $filePath;
        $listGuide->views = $request->input('views');
        $listGuide->display = $request->input('display');
        $listGuide->menu_order = $request->input('menu_order');
        $listGuide->adminid = $request->input('adminid');
        $listGuide->save();

        $guideDesc = GuideDesc::where('guide_id', $id)->first();
        if ($guideDesc) {
            $guideDesc->title = $request->input('title');
            $guideDesc->description = $request->input('description');
            $guideDesc->short = $request->input('short');
            $guideDesc->friendly_url = $request->input('friendly_url');
            $guideDesc->friendly_title = $request->input('friendly_title');
            $guideDesc->metakey = $request->input('metakey');
            $guideDesc->metadesc = $request->input('metadesc');
            $guideDesc->lang = $request->input('lang');
            $guideDesc->save();
        }
    }
}
<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\About;
use App\Models\AboutDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
class IntroductionController extends AbstractController
 {

    protected function getModel()
 {
        return new About();
    }

    public function index()
 {
    try {
      
        $about = About::with('aboutDesc')->get();
        $response = [
            'status' => 'success',
            'list' => $about,

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
  
        $about = new About();
        $aboutDesc = new AboutDesc();
        try {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
           
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            
            $filepath = $request->file('picture')->storeAs('introduction',$fileNameToStore);
            $about->fill([
                'picture' => $filePath,
                'parentid'=> $request->input('parentid'),
                'views' => $request->input('views'),
                'menu_order' => $request->input('menu_order'),
                'display' => $request->input('display'),
                'adminid' => $request->input('adminid')
            ])->save();
            $aboutDesc->about_id = $about->about_id;
            $aboutDesc->title = $request->input('title');
            $aboutDesc->description = $request->input('description');
            $aboutDesc->friendly_url = $request->input('friendly_url');
            $aboutDesc->friendly_title = $request->input('friendly_title');
            $aboutDesc->metakey = $request->input('metakey');
            $aboutDesc->metadesc = $request->input('metadesc');
            $aboutDesc->lang = $request->input('lang');
            $aboutDesc->save();

            $response = [
                'status' => 'success',
                'about' => $about,
                'aboutDesc' => $aboutDesc,
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
        $list = About::Find($id)->delete();
    }
    public function edit($id)
    {
        $listAboutDesc = About::with('aboutDesc')->find($id);
          return response()->json([
            'status'=> true,
            'about' => $listAboutDesc
        ]);
    }
    public function update(Request $request, $id)
    {   
        $about = new About();
        $aboutDesc = new AboutDesc();
        $listAbout = About::Find($id);
        
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
           
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            
            $filepath = $request->file('picture')->storeAs('introduction',$fileNameToStore);
        } else {   
            $filePath = $list->picture; 
        }
        
        $listAbout->cat_id = $request->input('cat_id');
        $listAbout->picture = $filePath;
        $listAbout->parentid = $request->input('parentid');
        $listAbout->views = $request->input('views');
        $listAbout->menu_order = $request->input('menu_order');
        $listAbout->display = $request->input('display');
        $listAbout->adminid = $request->input('adminid');
        $listAbout->save();

        $aboutDesc = AboutDesc::where('about_id', $id)->first();
        if ($aboutDesc) {
            $aboutDesc->about_id = $about->about_id;
            $aboutDesc->title = $request->input('title');
            $aboutDesc->description = $request->input('description');
            $aboutDesc->friendly_url = $request->input('friendly_url');
            $aboutDesc->friendly_title = $request->input('friendly_title');
            $aboutDesc->metakey = $request->input('metakey');
            $aboutDesc->metadesc = $request->input('metadesc');
            $aboutDesc->lang = $request->input('lang');
            $aboutDesc->save();
        }
    }
}
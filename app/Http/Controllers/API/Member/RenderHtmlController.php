<?php

namespace App\Http\Controllers\API\Member;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;


class RenderHtmlController extends Controller
{
    
    public function index()
    {
    
            $html='Công ty TNHH Vi Tính Nguyên Kim 100';
                    return response()->json([
                        'SERVICE_A_URL'=>$html,
                ]);
    
    }
    public function show(){
        $filePath = 'C:\xampp1\htdocs\frontend\FrontendUser\build\index.html';
        if (file_exists($filePath)) {
           $html = File::get($filePath);
           $html = str_replace('__DESCRIPTION__', 'New DESCRIPTION', $html);
           File::put($filePath, $html);
           return response()->json(['message' => 'Updated successfully']);
            
        } else {
            return response()->json([
                'status'=>false
            ]);
        }
    }

}
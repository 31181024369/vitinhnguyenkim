<?php

namespace App\Http\Controllers\API\Member;

use App\Models\Support;
use App\Models\SupportGroup;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SupportController extends Controller
{
   
    public function index()
    {
        try{
            $leagues = DB::table('support_group')
            ->join('support', 'support_group.groupName', '=', 'support.group')
            ->where('support.display',1)
            ->select('support_group.*','support.*')
            ->get();
            if(count($leagues) >= 1){
                foreach($leagues as $support){
                    if(strlen(strstr($support->group, ".")) > 0)
                    {
                        $support->group=str_replace(".",'',$support->group);
                    }
                    if($support->group){
                        $data[$support->group][] = [
                            'title' => $support->title,
                            'email' => $support->email,
                            'phone' => $support->phone
                        ];
                    }
                }
            }
            return response()->json([
                'status' => true,
                'data' => $data ?? []
            ]);
        }catch(Exception $e){ 
              return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
              ]);    
        }
    }
}

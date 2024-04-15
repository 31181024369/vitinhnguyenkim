<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\Faqs;
use App\Models\FaqsDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\Admin\AbstractController;
use Illuminate\Support\Facades\Http;

class FaqsController extends AbstractController
 {
    protected function getModel()
    {
           return new Faqs();
       }

    public function index()
    {
        try {
        
            $faqs = Faqs::with('faqsDesc')->get();
            $response = [
                'status' => 'success',
                'list' => $faqs,

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
        $faqs = new Faqs();
        $faqsDesc = new FaqsDesc();
        try {
            $faqs->fill([
                'cat_id' => $request->input('cat_id'),
                'cat_list'=> $request->input('cat_list'),
                'poster' =>  $request->input('poster'),
                'email_poster' => $request->input('email_poster'),
                'phone_poster' => $request->input('phone_poster'),
                'answer_by' => $request->input('answer_by'),
                'views' => $request->input('views'),
                'display' => $request->input('display'),
                'menu_order' => $request->input('menu_order'),
                'adminid' => $request->input('adminid')
            ])->save();
            $faqsDesc->faqs_id = $faqs->faqs_id;
            $faqsDesc->title = $request->input('title');
            $faqsDesc->description = $request->input('description');
            $faqsDesc->lang = $request->input('lang');
            $faqsDesc->save();

            $response = [
                'status' => 'success',
                'faqs' => $faqs,
                'faqsDesc' => $faqsDesc,
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
        $list = Faqs::Find($id)->delete();
    }
    public function edit($id)
    {
        $faqs = Faqs::with('faqsDesc')->get();
          return response()->json([
            'status'=> true,
            'faqs' => $faqs
        ]);
    }
    public function update(Request $request, $id)
    {   
        $faqs = new Faqs();
        $faqsDesc = new FaqsDesc();
        $listFaqs = Faqs::Find($id);
        
        $listFaqs->fill([
            'cat_id' => $request->input('cat_id'),
            'cat_list'=> $request->input('cat_list'),
            'poster' =>  $request->input('poster'),
            'email_poster' => $request->input('email_poster'),
            'phone_poster' => $request->input('phone_poster'),
            'answer_by' => $request->input('answer_by'),
            'views' => $request->input('views'),
            'display' => $request->input('display'),
            'menu_order' => $request->input('menu_order'),
            'adminid' => $request->input('adminid')
        ])->save();

        $faqsDesc = FaqsDesc::where('faqs_id', $id)->first();
        if ($faqsDesc) {
            $faqsDesc->title = $request->input('title');
            $faqsDesc->description = $request->input('description');
            $faqsDesc->lang = $request->input('lang');
            $faqsDesc->save();
        }
    }
 }
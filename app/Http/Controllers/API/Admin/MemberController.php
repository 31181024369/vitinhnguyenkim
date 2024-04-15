<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Admin;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\API\Admin\AbstractController;
use App\Http\Controllers\Controller;
use App\Exports\MemberExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberController extends Controller
{
    protected function getModel()
    {
        return new Member();
    }
    public static function paginate($items, $perPage = 5, $page = null)
     {
         $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
         $total = count($items);
         $currentpage = $page;
         $offset = ($currentpage * $perPage) - $perPage ;
         $itemstoshow = array_slice($items , $offset , $perPage);
         
         return new LengthAwarePaginator($itemstoshow ,$total   ,$perPage);
    }
    public function export(){
        $fileName = 'member_'.date('Y_m_d_H_i_s').'.xlsx';
        $export = (new MemberExport);


        $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];
    
        return response($fileContents, 200, $headers);
        // Excel::store($export, $fileName, 'public');
        // $fileUrl = Storage::url($fileName);
        // return Excel::download($export, 'member.xlsx');
    }

    public function index(Request $request)
    {
        try {
            $offset = $request->page ? $request->page : 1 ;

            $idAdmin = Auth::guard('admin')->user()->adminid;
           
            $AdminDepartment = Admin::find($idAdmin);
            if($AdminDepartment->status!=2)
            {
                if(empty($request->input('data'))||$request->input('data')=='undefined' ||$request->input('data')==''){

                    $query=Member::orderBy('mem_id','DESC');
                }
                else{
                    $query=Member::orderBy('mem_id','DESC')->where("username", 'like', '%' . $request->input('data') . '%')
                    ->orderBy('mem_id','DESC');
                }
                $countMember=count($query->get());
                $dataMember=$query->limit(10)
                ->offset(($offset-1)*10)->get();
                
                $result = [];
                foreach($dataMember as $member){
                    $companyId=$member->company;
                    $admin = Admin::where('adminid', $companyId)->first();
                    if($admin){
                        $admins=[
                            'adminid' => $admin->adminid,
                            'admin_name' => $admin->display_name,
                        ];
                    }
                    else{
                        $admins=[];
                    }
                    $result[] = [
                        'mem_id' => $member->mem_id,
                        'username' => $member->username,
                        'full_name' =>$member->full_name,
                        'MaKH' =>$member->MaKH,
                        'MaKHDinhDanh'=>$member->MaKHDinhDanh,
                        'status'=>$member->status,
                        'm_status'=>$member->m_status,
                        'created_at' =>$member->created_at,
                        'last_login' => $member ->last_login,
                        'admins' => $admins,
                    ];
                }
                //return $result;
                // $result=$this->paginate($result,10);
                return response()->json([
                    'status'=>true,
                    'list'=>$result,
                    'countMember'=>$countMember
                ]);
            }
            else{

            
                $members = Member::where('company',$AdminDepartment->adminid)->orderBy('mem_id','DESC')->get();
          
                $dataMember=null;
                if(count($members) > 0)
                {
                
                    if(empty($request->input('data'))||$request->input('data')=='undefined' ||$request->input('data')==''){

                        $query=Member::where('company',$AdminDepartment->adminid)->orderBy('mem_id','DESC');
                    
                    }
                    else{
                        $query=Member::where('company',$AdminDepartment->adminid)
                        ->where("username", 'like', '%' . $request->input('data') . '%')
                        ->orderBy('mem_id','DESC');
                    }
                    $countMember=count($query->get());
                    $dataMember=$query->limit(10)
                    ->offset(($offset-1)*10)->get();
                    $result = [];
                    foreach($dataMember as $member){
                        $companyId=$member->company;
                        $admin = Admin::where('adminid', $companyId)->first();
                        if($admin){
                            $admins=[
                                'adminid' => $admin->adminid,
                                'admin_name' => $admin->display_name,
                            ];
                        }
                        $result[] = [
                            'mem_id' => $member->mem_id,
                            'username' => $member->username,
                            'full_name' =>$member->full_name,
                            'MaKH' =>$member->MaKH,
                            'MaKHDinhDanh'=>$member->MaKHDinhDanh,
                            'status'=>$member->status,
                            'm_status'=>$member->m_status,
                            'created_at' =>$member->created_at,
                            'last_login' => $member ->last_login,
                            'admins' => $admins,
                        ];
                    }
                    //return $result;
                    //$result=$this->paginate($result,10);
                    return response()->json([
                        'status'=>true,
                        'list'=>$result,
                        'countMember'=>$countMember
                        
                    ]);


                }else{
                    return response()->json([
                        'status'=>true,
                        'message'=>'Không có member để quản lý'
                    ]);
                }
        }
        
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];

            return response()->json($response, 500);
        }

    }


    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {   
        //
    }

    public function show(Request $request, $id)
    {   
        try {
            $list = parent::show($request, $id);
            
            $response = [
                'status' => 'success',
                'list' => $list 
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

    public function edit($id)
    {
        $idAdmin = Auth::guard('admin')->user()->adminid;
       
        $AdminDepartment = Admin::find($idAdmin);

        // if($AdminDepartment->status !=2){
        $listMember = Member::find($id);
          return response()->json([
            'status'=> true,
            'member' => $listMember
        ]);
        // }else{
        //     return response()->json([
        //         'status'=>false,
        //         'message'=>'Bạn không có quyền phê duyệt member, chỉ có trưởng phòng ban mới được phép duyệt!!!'
        //     ]);
        // }
    }
   

    public function update(Request $request, $id)
    {   

        $listMember = Member::find($id);
        $listMember -> company = $request ->supportName;
        $listMember -> m_status = $request ->browsingStatus;
        $listMember -> status = $request->lockStatus;
        $listMember -> city_province = $request->city;
        $listMember -> MaKH = $request->makh;
        $listMember -> email = $request->email;
        $listMember -> gender = $request->gender;
        $listMember -> phone = $request->tel;
        $listMember -> Sdtcongty = $request->companyphone;
        $listMember -> emailcty = $request->emailcty;
        $listMember -> Masothue = $request->tax;
        $listMember -> Diachicongty = $request->companyaddress;
        $listMember -> mem_group = 2;
        $listMember -> full_name = $request->fullname;
        $listMember -> Tencongty = $request->companyname;
        $listMember -> ward = $request->ward;
        $listMember -> district= $request->district;
        $listMember -> MaKHDinhDanh = $request->otherMaKh;
        $listMember->save();
        if($request ->browsingStatus == 1)
        {
            //send Email
            $to = $listMember -> email;
            $data = [
                'subject' => 'ViTinhNguyenKim',
                'body' => 'Tài khoản của bạn đã được duyệt, bạn có thể đăng nhập!',
            ];
            Helper::sendMail($to,$data);
        }
        return response()->json([
            'status'=> true,
            'member' => $listMember
        ]);
    }

    public function destroy($id)
    {
        $list = Member::Find($id)->delete();
    }
}

<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use App\Helpers\Helper;

class AdministrationMemberController extends Controller
{
    public function index(Request $request)
    {
        if($request->data != '')
        {
            $listMember = Member::where('status', 0)->where('m_status',1)->where('MaKH','!=','')
            ->where("MaKH", 'like', '%' . $request->data . '%')->paginate(10);
        }
        else{
        $listMember = Member::where('status', 0)->where('m_status',1)->where('MaKH','!=','')->paginate(10);
        }

        return response()->json($listMember);
    }
    public function edit($id)
    {
        $member = Member::find($id);
        return response()->json($member);
    }
    public function update(Request $request, $id)
    {
        try{
            $data = $request->all();
            $username = isset($request->username) ? $request->username : '';
            $password = isset($request->password) ? $request->password : '';
            $full_name = isset($request->full_name) ? $request->full_name : '';
            $email = isset($request->email) ? $request->email : '';
            $phone = isset($request->phone) ? $request->phone : '';
            $tencongty = isset($request->tencongty) ? $request->tencongty : '';
            $masothue = isset($request->masothue) ? $request->masothue : '';
            $emailcty = isset($request->emailcty) ? $request->emailcty : '';
            $diachicongty = isset($request->diachicongty) ? $request->diachicongty : '';
            $sdtcongty = isset($request->sdtcongty) ? $request->sdtcongty : '';
            $address =  isset($request->address) ? $request->address : '';
            $company =  isset($request->company) ? $request->company : '';
            $status =  isset($request->status) ? $request->status : '';
            $date = Carbon::now('Asia/Ho_Chi_Minh');
            $timestamp = strtotime($date);
            $member = Member::find($id);
            $member -> update([
                 'status' => $status
            ]);
            $to = $email;
            $data = [
                'subject' => 'PCNguyenKim',
                'body' => 'Tài khoản của bạn đã được duyệt!',
            ];
            Helper::sendMail($to,$data);
            return response()->json([
                'status' => true
            ]);
            
        }catch(\Throwable $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false
            ]);
        }
    }
}

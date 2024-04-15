<?php

namespace App\Http\Controllers\API;

use Validator;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Admin;
use App\Helpers\Helper;
use App\Mail\MailNotify;
use App\Mail\ForgetPassword;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Http\Response;
use App\Observers\AdminObserver;
use App\Observers\MemberObserver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Rules\AuthControllerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Rules\ForgetPasswordValidate;
use App\Rules\ForgetPasswordChangeValidate;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        Admin::observe(AdminObserver::class);
    }
    public function login(Request $request)
    {
       
        $val = Validator::make($request->all(), [
            'username' => 'required|',
            'password' => 'required',
        ]);
  
        if ($val->fails()) {
            return response()->json($val->errors(), 202);
        }
        
        $admin = Admin::where('username','=',$request->username)->find();
        $abbreviation = "";
        $string = ucwords($admin->password);
        $words = explode(" ", "$string");
        foreach($words as $word){
            $abbreviation .= $word[0];
        }
        if(isset($admin) && $abbreviation != "$" && Hash::check($request->password,$admin->password)==false)
        {
            Admin::where('adminid', $admin->adminid)->first()->update(['pass' => Hash::make($request->password)]);
        }
        
        if( $admin && $abbreviation == "$" && Hash::check($request->password,$admin->password)){
            Auth::login($admin);
            if(Auth::check()){
                $success = $request->user()->createToken('Admin')->accessToken;
                $notification = Notifications::where('notifiable_id',$admin->adminid)->get();
                return response()->json([
                    'status' => true,
                    'token' => $success,
                    'notification' => $notification,
                    'admin' => $admin
                ]);
            }else{
                return response()->json([
                        'status' => false
                ]);
            }
        }else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    public function register(Request $request)
    {
        $validate = AuthControllerRequest::validate($request->all());
        if ($validate->fails()) {
            return response()->json($validate->errors(), 202);
        }
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        $timestamp = strtotime($date);
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
        $district =  isset($request->district) ? $request->district : '';
        $cityProvince =  isset($request->city_province) ? $request->city_province : '';
        $MaKH = isset($request->MaKH) ? $request->MaKH : '';
        $isExist = Member::select("*")
            ->where("email", $email)
            ->exists();
        if ($isExist) {
            return response()->json(['message'=>'Tài khoản đã tồn tại', 'status' => 0]);
        }else{
            $member = Member::create([
                'mem_group' => '0','username' => $username,'mem_code' => '',
                'email' => $email,'password' => Hash::make($password),'activate_code' => '',
                'address' => $address,'company' => $company,'full_name' => $full_name,
                'gender' => '0','birthday' => '', 'avatar' => '','phone' => $phone,
                'buildpc' => '', 'newsletter' => '1','date_join' => $timestamp,
                'last_login' => '0', 'm_status' => '0','mem_point' => '0',
                'mem_point_use' => '0','api_type' => '','api_user' => '',
                'api_pass' => '','menu_order' => '0','Tencongty' => $tencongty,
                'Masothue' => $masothue,'Diachicongty' => $diachicongty,
                'Sdtcongty' => $sdtcongty, 'emailcty' => $emailcty,
                'idmacoupon' => '',  'MaKH' => $MaKH,'remember_token' => '',
                'district' => $district, 'city_province' => $cityProvince,
                'MaKHDinhDanh' => $username.''.$district.''.$cityProvince, 
            ]);
            $to = $email;
            $data = [
                'subject' => 'ViTinhNguyenKim',
                'body' => 'Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.',
            ];
            Helper::sendMail($to,$data);
            return response()->json([
                'message'=> 'Đăng ký thành công',
                'data' => [
                    'Id' => $member->MaKH,
                    'MST' => $member->Masothue,
                    'TenDD' => $member->username,
                    'Email' => $member->email,
                    'Phone' => $member->phone,
                    'Tencongty' => $member->tencongty,
                    'Diachicongty' => $member->diachicongty,
                ],
                'status'=> true,
            ]);
        }
    }
     public function forgetPassword(Request $request)
     {
         $validator = ForgetPasswordValidate::validate($request->all());
     
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }
         $email = $request->input('email');
         Mail::to($email)->send(new ForgetPassword('testtest123'));
     
         return response()->json(['message' => 'send email success', 'status' => true]);
     }
     public function forgetPasswordChange(Request $request)
     {
        $validator = ForgetPasswordChangeValidate::validate($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $member = Member::where('email', $email)->first();
        if (!$member) {
            return response()->json(['error' => 'Email not found'], 404);
        }
        $member->password = Hash::make($password);
        $member->save();
        return response()->json(['message'=>'change success', 'status' => true]);
     }
     public function logout(Request $request)
     {
        $request->user('member')->token()->revoke();
        return response()->json(['status'=>true]);
     }
     public function update(Request $request)
     {
        try{
            DB::beginTransaction();
            $memberId = Member::find(Auth::guard('member')->user()->mem_id);
            $data = $request->all();
            $memberId->username   = $data['username'];
            $memberId->full_name  = $data['full_name'] ?? '';
            $memberId->email      = $data['email'] ?? '';
            $memberId->phone      = $data['phone'] ?? '';
            $memberId->tencongty  = $data['tencongty'] ?? '';
            $memberId->masothue   = $data['masothue'] ?? '';
            $memberId->emailcty   = $data['emailcty'] ?? '';
            $memberId->diachicongty = $data['diachicongty'] ?? '';
            $memberId->sdtcongty  = $data['sdtcongty'] ?? '';
            $memberId->address    = $data['address'] ?? '';
            $memberId->company    = $data['company'] ?? '';
            $memberId->district   = $data['district'] ?? '';
            $memberId->city_province = $data['city_province'] ?? '';
            $memberId->MaKH = $data['MaKH'] ?? '';
            $memberId->save();
            DB::commit();
            return response()->json([
                'member' => $memberId,
                'status' => true
            ]);
        }catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
     }
     public function information()
     {
        try{
            $memId = Member::find(Auth::guard('member')->user()->mem_id);
            return response()->json([
              'member' => $memId,
              'status' => true
            ]);
        }catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ], 422);
        }
     }
}
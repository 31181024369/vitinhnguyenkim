<?php

namespace App\Http\Controllers\API\Member;

use Validator;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Admin;
use App\Models\Form;
use App\Models\StatisticsPages;
use App\Helpers\Helper;

use App\Mail\MailNotify;
use App\Mail\InformationMember;
use App\Mail\ForgetPassword;
use App\Mail\fillFormMail;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Http\Response;
use App\Observers\MemberObserver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Rules\AuthControllerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Rules\ForgetPasswordValidate;
use App\Rules\ForgetPasswordChangeValidate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
       
        Member::observe(MemberObserver::class);
    }
    public function login(Request $request)
    {
      
        try{
           
            $val = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);
            if ($val->fails()) {
                return response()->json($val->errors(), 202);
            }
            
            $member=Member::where('username',$request->username)
            ->first();
           
            
            $abbreviation = "";
            $string = ucwords($member->password);
            $words = explode(" ", "$string");
            foreach($words as $word){
                $abbreviation .= $word[0];
            }
            
            if(isset($member) && $abbreviation != "$" && Hash::check($request->password,$member->password)==false)
            {
                Member::where('mem_id', $member->mem_id)->first()->update(['password' => Hash::make($request->password)]);
            }
            
            if( $member && $abbreviation == "$" && Hash::check($request->password,$member->password)){
               
                Auth::login($member);
                
                
                if($member->m_status == 0)
                {
                  
                    return response()->json([
                        'status' => false,
                        'mes' => 'account false'
                    ]);
                }
                if($member->status == 1)
                {
                   
                    return response()->json([
                        'status' => false,
                        'mes' => 'account block'
                    ]);
                }
               
                if(Auth::check()){
                   
                    $success = $request->user()->createToken('Member')->accessToken;
                    $notification = Notifications::where('notifiable_id',$member->mem_id)->get();
                    return response()->json([
                        'status' => true,
                        'token' => $success,
                        'notification' => $notification,
                        'admin' => $member
                    ]);
                }else{
                    return response()->json([
                            'status' => false
                    ]);
                }
            }else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function register(Request $request)
    {
        
        try{
            $validator = AuthControllerRequest::validate($request->all());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $date = Carbon::now('Asia/Ho_Chi_Minh');
            $timestamp = strtotime($date);
            // check null fill
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
            $ward =  isset($request->ward) ? $request->ward : '';
            $cityProvince =  isset($request->city_province) ? $request->city_province : '';
            $MaKH = isset($request->MaKH) ? $request->MaKH : '';
            $isExistEmail = Member::where("email", $email)
                ->first();
            $isExistMst = Member::where("masothue", $masothue)
            ->first();
            $isExistUsername = Member::where("username", $username)
            ->first();
            if($masothue != '') {
                if($isExistMst) {
                    return response()->json(['message'=>'existMST', 'status' => false]);
                } 
            }
            if ($isExistUsername ) {
                return response()->json(['message'=>'existUserName', 'status' => false]);
            }
            if ($isExistEmail ) {
                return response()->json(['message'=>'error', 'status' => false]);
            }else{
                $member = Member::create([
                    'mem_group' => '2',
                    'username' => $username,
                    'mem_code' => '',
                    'email' => $email,
                    'password' => Hash::make($password),
                    'activate_code' => '',
                    'address' => $address,
                    'company' => $company,
                    'full_name' => $full_name,
                    'gender' => '0',
                    'birthday' => '',
                    'avatar' => '',
                    'phone' => $phone,
                    'buildpc' => '', 
                    'newsletter' => '1',
                    'date_join' => $timestamp,
                    'last_login' => '0', 
                    'm_status' => '0',
                    'status' => '0',
                    'mem_point' => '0',
                    'mem_point_use' => '0',
                    'api_type' => '',
                    'api_user' => '',
                    'api_pass' => '',
                    'menu_order' => '0',
                    'Tencongty' => $tencongty,
                    'Masothue' => $masothue,
                    'Diachicongty' => $diachicongty,
                    'Sdtcongty' => $sdtcongty, 
                    'emailcty' => $emailcty,
                    'idmacoupon' => '',  
                    'MaKH' => $MaKH,
                    'remember_token' => '',
                    'ward' => $ward,
                    'district' => $district, 
                    'city_province' => $cityProvince,
                    'MaKHDinhDanh' => $username.''.$district.''.$cityProvince, 
                ]);
                $to = $email;
                $data = [
                    'subject' => 'ViTinhNguyenKim',
                    'body' => 'Cảm ơn bạn đã đăng ký tài khoản thành công tại Vi Tính Nguyên Kim. ',
                ];
                Helper::sendMail($to,$data);
               
                // Mail::to($email)
                // ->send(new MailNotify($data));

                $info=[
                    'username'=>$username,
                    'email' => $email,
                    'address' => $address,
                    'full_name' => $full_name,
                    'phone' => $phone,
                    'date_join' => $timestamp,
                    'Tencongty' => $tencongty,
                    'Masothue' => $masothue,
                    'Diachicongty' => $diachicongty,
                    'Sdtcongty' => $sdtcongty, 
                    'emailcty' => $emailcty,
                    'MaKH' => $MaKH,
                    'ward' => $ward,
                    'district' => $district, 
                    'city_province' => $cityProvince,
                    'MaKHDinhDanh' => $username.''.$district.''.$cityProvince, 
                ];
                Mail::to("long542.nt@gmail.com")
                ->send(new InformationMember($info));


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
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
     public function forgetPassword(Request $request)
     {
        try
        {
            $validator = ForgetPasswordValidate::validate($request->all());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $email = $request->input('email');
            $passwordToken=Str::random(6);
            $member = Member::where('email','=', $email)->first();
            $member->password_token= $passwordToken;
            $member->save();
            $diem=[
                'id_member' => $member->mem_id,
                'username'=> $member->username,
                'password_token'=>$passwordToken
            ];
        
            Mail::to($email)->send(new ForgetPassword($diem));
            return response()->json(['message' => 'send email success', 'status' => true]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
     }
     public function forgetPasswordChange(Request $request)
     {
       try{
            $validator = ForgetPasswordChangeValidate::validate($request->all());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $passwordToken = $request->input('password_token');
            $passwordNew = Hash::make($request->input('password_new'));
            $member = Member::where('password_token', $passwordToken)->first();
            if (!$member) {
                return response()->json(['error' => 'Password Token not found'], 404);
            }
            $member->password = $passwordNew;
            $member->password_token=null;
            $member->save();
            return response()->json(['message'=>'change success', 'status' => true]);
        }
        catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ], 422);
        }
     }
     public function logout(Request $request)
     {
        // Auth::guard('member')->logout();
        try{
            $request->user('member')->token()->revoke();
            return response()->json(['status'=>true]);
        }catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ], 422);
        }
     }
     public function update(Request $request)
     {
        try{
            $val = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                // 'MaKH' => 'required',
            ]);
            if ($val->fails()) {
                return response()->json($val->errors(), 202);
            }

            DB::beginTransaction();
            $memberId = Member::find(Auth::guard('member')->user()->mem_id);
            $data= $request->all();
            //$memberId->username   = $data['username'];
            //$memberId->full_name  = $data['full_name'] ?? '';
            $memberId->email      = $data['email'] ?? '';
            $memberId->phone      = $data['phone'] ?? '';
            $memberId->password      = $data['password']?Hash::make($data['password']):Auth::guard('member')->user()->password ;
            //$memberId->Tencongty  = $data['Tencongty'] ?? '';
            //
            //$memberId->Masothue   = $data['Masothue'] ?? '';
            //$memberId->emailcty   = $data['emailcty'] ?? '';
            //$memberId->Diachicongty = $data['Diachicongty'] ?? '';
            //$memberId->Sdtcongty  = $data['Sdtcongty'] ?? '';
            //$memberId->address    = $data['address'] ?? '';
            //$memberId->district   = $data['district'] ?? '';
            //$memberId->ward   = $data['ward'] ?? '';

            // $memberId->city_province = $data['city_province'] ?? '';
            // $memberId->MaKH = $data['MaKH'] ?? '';
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
            if(Auth::guard('member')->user() != null)
            {
                
                $memId = Member::with('adminSupport')->find(Auth::guard('member')->user()->mem_id);
                
                return response()->json([
                'member' => $memId,
                'status' => true,
                
                ]);
            }
        }catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ], 422);
        }
     }
     public function form(Request $request){
        try{
            $form=new Form();
            $form->title=$request->title??'';
            $form->gmail=$request->gmail??'';
            $form->phone=$request->phone?? '';
            $form->content=$request->content?? '';
            $form->save();
            Mail::to('longhoangphp@gmail.com')->send(new fillFormMail($form));
            Mail::to('bao.bui@chinhnhan.vn')->send(new fillFormMail($form));
            Mail::to('thepdt@nguyenkimvn.vn')->send(new fillFormMail($form));
            return response()->json([
                'status'=>true
            ]);
            

        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
     }
}
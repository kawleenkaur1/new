<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\OtpUser;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CustomNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use CustomNotifications;
    //
    public function index()
    {
        echo "hello customers";
    }

    public function check_if_customer_email_exists($email)
    {
        $user = User::where('email',$email)->where('user_type',2)->first();
        return $user;
    }
    public function check_if_customer_phone_exists($phone)
    {
        $user = User::where('phone',$phone)->where('user_type',2)->first();
        return $user;
    }

    public function signup_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'email|string',
            'phone' => 'string|max:10',
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return response()->json(['status' => false,'message'=>$message]);
        }
        $phone_otp = false;
        $email_otp = false;
        $input = $request->all();
        if(empty($input['phone'])){
            return response()->json(['status' => false,'message'=>'Please add phone number']);
        }

        if(isset($input['referral_code']) && !empty($input['referral_code'])){
            $referral_code=trim($input['referral_code']);
            $ruser=User::where('referral_code',$referral_code)->first();
            if(!$ruser){
                return response()->json(['status' => false,'message'=>'Please enter valid referral code.']);
            }
        }

        if(!empty($input['phone'])){
            $phone_otp = true;
            $sent_source = 'Phone number';
        }elseif(!empty($input['email'])){
            $email_otp = true;
            $sent_source = 'Email address';
        }

        if(!empty($input['phone'])){
            if($this->check_if_customer_phone_exists($input['phone'])){
                return response()->json(['status' => false,'message'=>'Phone number already exists']);
            }
        }
        if(!empty($input['email'])){
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                return response()->json(['status' => false,'message'=>'Your email address is not valid.']);
            }
            if($this->check_if_customer_email_exists($input['email'])){
                return response()->json(['status' => false,'message'=>'Email address already exists']);
            }
        }
        $otp = yt_generateOTP();
        yt_send_otp($otp,$input['phone']);

        $arr = [
            'email'=>$input['email'],
            'name'=>$input['name'],
            'phone'=>$input['phone'],
            'otp'=>$otp,
            'email_verified'=>$email_otp ? 1 : 0,
            'phone_verified'=>$phone_otp ? 1 : 0,
            'user_type'=>2,
            'referral_from'=>isset($input['referral_code']) ? $input['referral_code'] : '',
            'created_at'=>$date = date('Y-m-d H:i:s'),
            'updated_at'=>$date
        ];

        $create = OtpUser::create($arr);
        return response()->json(['status' => true,'id'=>$create->id,'otp'=>$otp,
        'email_otp'=>$email_otp,
        'phone_otp'=>$phone_otp,
        'message'=>'OTP Sent on your registered '.$sent_source.' successfully.']);
    }

    public function signup_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'otp'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return response()->json(['status' => false,'message'=>$message]);
        }
        $input = $request->all();
        $get_otp_user = OtpUser::where('id',$input['id'])->first();
        // dd($get_otp_user);
        if(empty($get_otp_user)){
            return response()->json(['status' => false,'message'=>'Invalid OTP.']);
        }

        if(trim($input['otp']) !== $get_otp_user->otp){
            return response()->json(['status' => false,'message'=>'Invalid OTP.']);
        }

        $d['name'] = $get_otp_user->name;
        $d['email'] = $get_otp_user->email;
        $d['phone'] = $get_otp_user->phone;
        $d['email_verified'] = $get_otp_user->email_verified;
        $d['phone_verified'] = $get_otp_user->phone_verified;

        $d['email_verified_at'] = $get_otp_user->email_verified == 1 ? date('Y-m-d H:i:s') : null;
        $d['phone_verified_at'] = $get_otp_user->phone_verified == 1 ? date('Y-m-d H:i:s') : null;


        $d['user_type'] = $get_otp_user->user_type;
        $d['device_id'] = $input['device_id'];
        $d['device_type'] = $input['device_type'];
        $d['device_token'] = $input['device_token'];
        $d['model_name'] = $input['model_name'];
        $d['password'] = bcrypt('rozana@123');
        // $d['referral_from']=
        $refered_user = User::where('referral_code',$get_otp_user->referral_from)->first();
        if($refered_user){
            $d['referral_from']=$get_otp_user->referral_from;
        }


        if(!empty($get_otp_user->phone)){
            if($this->check_if_customer_phone_exists($get_otp_user->phone)){
                return response()->json(['status' => false,'message'=>'Phone number already exists']);
            }
        }
        if(!empty($get_otp_user->email)){
            if (!filter_var($get_otp_user->email, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['status' => false,'message'=>'Your email address is not valid.']);
            }
            if($this->check_if_customer_email_exists($get_otp_user->email)){
                return response()->json(['status' => false,'message'=>'Email address already exists']);
            }
        }

        $user = User::create($d);
        $user->referral_code=rz_referral_code().$user->id;
        $user->save();
        $update_user=User::where('id',$user->id)->first();
        $settings=Setting::first();
        if($refered_user){


            /**refer to */
            $old_wallet=$update_user->wallet;
           
            if($settings){
                $amount=floatval($settings->referral_rewards);
            }else{
                $amount=10;
            }

            $rf=[
                'refer_from'=>$refered_user->id,
                'refer_to'=>$user->id,
                'earn_points'=>$amount
            ];
            $referral=Referral::create($rf);

            $updated_wallet = $old_wallet + $amount;
            $update_user->wallet=$updated_wallet;
            $update_user->save();
            $txn=[
                'user_id'=>$update_user->id,
                'txn_name'=>'Referral Reward',
                'order_txn_id'=>time().$update_user->id,
                'payment_mode'=>'wallet',
                'type'=>'credit',
                'old_wallet'=>$old_wallet,
                'txn_amount'=>$amount,
                'txn_for'=>'referral_reward',
                'update_wallet'=>$updated_wallet,
                'status'=>1,
                'txn_mode'=>'other',
                'created_at'=>$date=date('Y-m-d H:i:s'),
                'updated_at'=>$date
            ];
            $create = Transaction::create($txn);

             /**refer from */
             $old_wallet2=$refered_user->wallet;
             $updated_wallet2 = $old_wallet2 + $amount;
             $refered_user->wallet=$updated_wallet2;
             $refered_user->save();
             $txn=[
                 'user_id'=>$refered_user->id,
                 'payment_mode'=>'wallet',
                 'txn_name'=>'Referral Reward',
                 'order_txn_id'=>time().$refered_user->id,
                 'type'=>'credit',
                 'old_wallet'=>$old_wallet2,
                 'txn_amount'=>$amount,
                 'update_wallet'=>$updated_wallet2,
                 'status'=>1,
                 'txn_for'=>'referral_reward',
                 'txn_mode'=>'other',
                 'created_at'=>$date=date('Y-m-d H:i:s'),
                 'updated_at'=>$date
             ];
             $create = Transaction::create($txn);
             $this->refferal_notification($refered_user,$update_user,$amount);

           

        }
         /**cashback */
         $cashback_old_wallet=floatval($update_user->cashback_wallet);
         $cashback_amount = floatval($settings->cashback_signup);
         $updated_cashback_wallet = $cashback_old_wallet +$cashback_amount;
        
         if(intval($cashback_amount)){
            $update_user->cashback_wallet=$updated_cashback_wallet;
            $update_user->save();
             $txn=[
                 'user_id'=>$update_user->id,
                 'payment_mode'=>'wallet',
                 'txn_name'=>'Cashback Reward for Signup',
                 'order_txn_id'=>time().$update_user->id,
                 'type'=>'credit',
                 'old_wallet'=>$cashback_old_wallet,
                 'txn_amount'=>$cashback_amount,
                 'update_wallet'=>$updated_cashback_wallet,
                 'status'=>1,
                 'txn_for'=>'cashback_reward',
                 'txn_mode'=>'other',
                 'created_at'=>$date=date('Y-m-d H:i:s'),
                 'updated_at'=>$date,
                 'wallet_type'=>2
             ];
             $create = Transaction::create($txn);
             $this->cashback_wallet_notification($update_user,$cashback_amount);
         }
        OtpUser::where('id',$input['id'])->orWhere('email',$get_otp_user->email)->orWhere('phone',$get_otp_user->phone)->delete();

        $token =  $user->createToken('MyApp')->accessToken;
        return response()->json([
            'status'=>true,
            'user'=>$update_user,
            'token'=>$token
        ]);
    }

    public function resend_otp_signup(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return response()->json(['status' => false,'message'=>$message]);
        }

        $get_otp_user = DB::table('otp_users')->where('id',$input['id'])->first();
        if(empty($get_otp_user)){
            return response()->json(['status' => false,'message'=>'Invalid Request.']);
        }
        $otp = yt_generateOTP();
        yt_send_otp($otp,$get_otp_user->phone);
        
        OtpUser::where('id',$input['id'])->update(['otp'=>$otp,
        'created_at'=>$date = date('Y-m-d H:i:s'),
        'updated_at'=>$date
        ]);
        return response()->json([
            'status'=>true,
            'otp'=>$otp,
            'message'=>'OTP sent successfully.'
        ]);
    }

    public function resend_otp(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email_phone'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return response()->json(['status' => false,'message'=>$message]);
        }
        $string = $input['email_phone'];
        $otp = yt_generateOTP();
        $email_otp = false;
        $phone_otp = false;
        if(yt_checkEmail($string)){

            $sent_source = 'Email address';
            $email_otp = true;

            $get_otp_user = OtpUser::where('email',$string)->orderDesc()->first();
            $get_otp_user->otp=$otp;
            $get_otp_user->save();
        }else{

            $sent_source = 'Phone number';
            $phone_otp = true;
            $get_otp_user = OtpUser::where('phone',$string)->orderDesc()->first();
            $get_otp_user->otp=$otp;
            $get_otp_user->save();
        }
        if($get_otp_user){
            yt_send_otp($otp,$string);
            return response()->json(['status' => true,'id'=>$get_otp_user->id,'otp'=>$otp,
            'email_otp'=>$email_otp,
            'phone_otp'=>$phone_otp,
            'message'=>'OTP Sent on your registered '.$sent_source.' successfully.']);
        }
        return response()->json(['status' => false,
        'message'=>'something went wrong.']);
    }

    public function login_otp(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email_phone' => 'string',
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return response()->json(['status' => false,'message'=>$message]);
        }
        $phone_otp = false;
        $email_otp = false;

        $string = $input['email_phone'];
        if(yt_checkEmail($string)){
            if(!$this->check_if_customer_email_exists($string)){
                return response()->json(['status' => false,'message'=>'Email address not found in our system. Please signup first.']);
            }
            $sent_source = 'Email address';
            $email_otp = true;
        }else{
            if(!$this->check_if_customer_phone_exists($string)){
                return response()->json(['status' => false,'message'=>'Phone number not found in our system. Please signup first.']);
            }
            $sent_source = 'Phone number';
            $phone_otp = true;
        }
        $otp = yt_generateOTP();
        yt_send_otp($otp,$string);

        $arr = [
            'email'=>$email_otp ? $string : '',
            'name'=>'',
            'phone'=>$phone_otp ? $string : '',
            'otp'=>$otp,
            'email_verified'=>$email_otp ? 1 : 0,
            'phone_verified'=>$phone_otp ? 1 : 0,
            'user_type'=>2,
            'created_at'=>$date = date('Y-m-d H:i:s'),
            'updated_at'=>$date
        ];

        $create =OtpUser::create($arr);
        return response()->json(['status' => true,'id'=>$create->id,'otp'=>$otp,
        'email_otp'=>$email_otp,
        'phone_otp'=>$phone_otp,
        'message'=>'OTP Sent on your registered '.$sent_source.' successfully.']);
    }

    public function login_verify(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'=>'required',
            'otp'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return response()->json(['status' => false,'message'=>$message]);
        }

        $get_otp_user = OtpUser::where('id',$input['id'])->first();
        if(empty($get_otp_user)){
            return response()->json(['status' => false,'message'=>'Invalid OTP.']);
        }

        if(trim($input['otp']) !== $get_otp_user->otp){
            return response()->json(['status' => false,'message'=>'Invalid OTP.']);
        }

        if(!empty($get_otp_user->email)){
            $user = $this->check_if_customer_email_exists($get_otp_user->email);
        }else{
            $user =  $this->check_if_customer_phone_exists($get_otp_user->phone);
        }
        if(!$user){
            return response()->json(['status' => false,'message'=>'Something went wrong. Please try again later']);
        }

        $user->device_id = $input['device_id'];
        $user->device_type = $input['device_type'];
        $user->device_token = $input['device_token'];
        $user->model_name = $input['model_name'];
        $user->save();
        OtpUser::where('id',$input['id'])->delete();
        $token =  $user->createToken('MyApp')->accessToken;
        return response()->json([
            'status'=>true,
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function get_user_profile(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'status'=>false,
                'message'=>'User not found.'
            ]);
        }
        $user = User::where('id',$user->id)->first();
        return response()->json([
            'status'=>true,
            'user'=>$user
        ]);
    }

    public function get_user_referral_code(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'status'=>false,
                'message'=>'User not found.'
            ]);
        }
        $user = User::where('id',$user->id)->first();
        return response()->json([
            'status'=>true,
            'referrral_code'=>$user->referral_code,
            //'user'=>$user
        ]);
    }

    public function edit_profile(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'name'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return response()->json(['status' => false,'message'=>$message]);
        }
        $user_id=$input['user_id'];
        $arr['name']=$input['name'];
        // $arr['email']=$input['email'];
        if($input['flag']==1){
            if($_FILES['image']['size']>0){
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/user/'), $imageName);
                $arr['image'] = $imageName;
            }
        }
        $update=User::where('id',$user_id)->update($arr);
        return response()->json([
            'status'=>true,
            'message'=>'Updated',
            'user'=>User::where('id',$user_id)->first()
        ]);
    }

}

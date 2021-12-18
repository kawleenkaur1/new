<?php

use App\Models\Delivery;
use App\Models\Frequency;
use App\Models\Inventory;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Vacation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Psr7\str;

if (!function_exists('yt_validator_error_messages')) {
    function yt_validator_error_messages($validator)
    {
        $string = '';
        $errors = $validator->errors();
        if ($errors->any()){
            foreach ($errors->all() as $error){
                $string .= $error.", ";
            }
        }
        return rtrim($string,', ');
    }
}

if (!function_exists('yt_send_otp')) {

    function yt_send_otp($otp, $mobile)
    {
        $message = "Your verification code is ".$otp;

        // http://34.217.72.45:6005/api/v2/SendSMS?SenderId=GCSLAB&Is_Unicode=true&Is_Flash=false&Message=TEST&MobileNumbers=9896449941&ApiKey=fT23tdl0FKlNbORqw2wLYOh7FyXk+G+N/F0HcXBBWF4=&ClientId=88dee2f7-2e86-40a4-bc9e-9e8f71a9bb68
        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://34.217.72.45:6005/api/v2/SendSMS?SenderId=GCSLAB&Is_Unicode=true&Is_Flash=false&Message=".urlencode($message)."&MobileNumbers=".urlencode($mobile)."&ApiKey=fT23tdl0FKlNbORqw2wLYOh7FyXk+G+N/F0HcXBBWF4=&ClientId=88dee2f7-2e86-40a4-bc9e-9e8f71a9bb68",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        // echo "hello";die;
        // print_r($response);die;


        curl_close($curl);


        if ($err) {
            // $data1 = array('status'=>false,'message'=>'Something error happen!!please contact us to developer.');
            return false;
        } else {
            // $data1 = array("status"=>true,"otp"=>$data["otp"],"message"=>"otp send successfully");
            return true;
        }
    }
}

function get_product_stock($product_id)
{
    $sum_in = Inventory::where('product_id',$product_id)->inStock()->sum('stock');
    $sum_out = Inventory::where('product_id',$product_id)->outStock()->sum('stock');
    $total = $sum_in - $sum_out;
    if($total<0){
        $total = 0;
    }

    return $total;
    
}

if (!function_exists('yt_generateOTP')) {

function yt_generateOTP()
{

    $otp = mt_rand(1000, 9999);
    return $otp;
}
}

if (!function_exists('rz_currency')) {

    function rz_currency()
    {

        return "&#x20B9;";
    }
}


if (!function_exists('yt_checkEmail')) {

function yt_checkEmail($email) {
    $find1 = strpos($email, '@');
    $find2 = strpos($email, '.');
    return ($find1 !== false && $find2 !== false && $find2 > $find1);
 }
}


if (!function_exists('yt_api_response')) {

    function yt_api_response($arr,$status=200) {
        return response()->json($arr,$status);

    }
}

if (!function_exists('yt_app_settings')) {

    function yt_app_settings()
    {
        $common_settings = Setting::first();
        return $common_settings;
    }
}





if (!function_exists('yt_fcm_push_notification_deliveryboy')) {

    function yt_fcm_push_notification_deliveryboy($registatoin_ids, $message)
    {
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';

        $API_SERVER_KEY = 'AAAAm2sa9cI:APA91bFL9Od5CKWzlyPdJR2uOxQP_Z4D-WDJRJ6CjJF1-4jk459mLsQlloP7uQGSkGO-Efhdbn2_Su5zXfB0-LkVsMCVDm6zyzLwIH3Wyy5MKo-Drv1Rj48s1066SMS9AQSgwFI6pVAa';

        if (!is_array($registatoin_ids)) {
            $device_tokens = [$registatoin_ids];
        } else {
            $device_tokens = $registatoin_ids;
        }
        $fields = array(
            'registration_ids' => $device_tokens,
            'data' => $message,
            'notification' => $message,
            'priority'=>'high'
            // 'sound'=>'default'
        );
        $headers = array(
            'Authorization:key=' . $API_SERVER_KEY,
            'Content-Type:application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post

        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        // print_r($result);
        return $result;
        // print_r($result);
    }
}


if (!function_exists('yt_fcm_push_notification')) {

    function yt_fcm_push_notification($registatoin_ids, $message)
    {
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';

        $API_SERVER_KEY = 'AAAAwgE-MkI:APA91bGAs8kLekn-wH8GVyMfCUt64rGUHPjC_p0FE5dO8eE5_UbZOcegCSM4-AtKj8GL8-A-bAozBCECE0cEIlzzKTWsP9pr_mjIgZYlifOnWNnDQMkcNHWAFF3GW7VvC51MDQsIVfUD';

        if (!is_array($registatoin_ids)) {
            $device_tokens = [$registatoin_ids];
        } else {
            $device_tokens = $registatoin_ids;
        }
        $fields = array(
            'registration_ids' => $device_tokens,
            'data' => $message,
            'notification' => $message,
            'priority'=>'high'
            // 'sound'=>'default'
        );
        $headers = array(
            'Authorization:key=' . $API_SERVER_KEY,
            'Content-Type:application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post

        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        // print_r($result);
        return $result;
        // print_r($result);
    }
}

if (!function_exists('yt_notify_msg')) {

    function yt_notify_msg($title, $body, $details = [])
    {
        return  array(
            'body' => $body,
            'data' => $details,
            'title' => $title,
            'sound' => 'default',
            "icon" => "ic_launcher"

        );
    }
}


function rz_have_vacation($user_id,$date,$vacations_str='')
{
    $vacation_arr=explode('|',$vacations_str);
    if(!empty($vacation_arr)){
        if(in_array($date, $vacation_arr)){
            return true;
        }
    }
    $str_time=strtotime($date);
    $vacation=Vacation::where('user_id',$user_id)->orderBy('id','desc')->first();
    if($vacation){
        $start_date=$vacation->start_date ? strtotime($vacation->start_date) : 0;
        $end_date = $vacation->end_date ? strtotime($vacation->end_date) : 0;
        if($start_date && $end_date){
            if($str_time >= $start_date && $str_time <= $end_date){
                return true;
            }
        }
    }
    return false;
}

// Function to get all the dates in given range
function rz_getDatesFromRange($start, $end, $format = 'Y-m-d') {

    // Declare an empty array
    $array = array();

    // Variable that store the date interval
    // of period 1 day
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    // Use loop to store date into array
    foreach($period as $date) {
        $array[] = $date->format($format);
    }

    // Return the array elements
    return $array;
}

function rz_have_skip_days($order,$date)
{
    $skip_days=$order->skip_days;
    $dates_arr=rz_getDatesFromRange($order->start_date,$order->end_date);
    $count=count($dates_arr);
    $skip_dates_arr=[];
    $non_skip_dates_arr=[];

    if($skip_days != 0){
        $i = 0;
        foreach($dates_arr as $value) {
            if ($i++ % ($skip_days+1) == 0) {
                $non_skip_dates_arr[] = $value;
            }else{
                $skip_dates_arr[]=$value;
            }
        }
    }

    print_r($skip_dates_arr);
}


if (!function_exists('yt_notification')) {

    function yt_notification($details=[],$user_id=0,$type='single', $data = [],$user_type=2)
    {


        $arr = [
            'user_id'=>$user_id,
            'title'=>$details['title'],
            'body'=>$details['body'],
            'type'=>$type == 'single' ? 1 : 2,
            'user_type'=>$user_type,
            'payload'=>json_encode($data),
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s')
        ];


        DB::table('notifications')->insert($arr);
        // dd($arr);
        if($type == 'single'){
           $device_token = User::where('id',$user_id)->pluck('device_token')->toArray();
           if(!empty($device_token)){
               if($user_type == 3){ /**delivery boy */
                    yt_fcm_push_notification_deliveryboy($device_token,$details);

               }else{ /**customer */
                    yt_fcm_push_notification($device_token,$details);
                    rz_send_ios_notification($device_token,$details['title'].' | '.$details['body']);
               }

           }
        }else{
            if($user_type == 2){
                $user_tokens = User::where('user_type',2)->where('device_token','!=','')->pluck('device_token')->toArray();
            }else{
                $user_tokens = User::where('user_type',3)->where('device_token','!=','')->pluck('device_token')->toArray();
            }
            // $user_tokens =(array_filter($user_tokens));
            if(!empty($user_tokens)){
                if($user_type == 3){
                    yt_fcm_push_notification_deliveryboy($user_tokens,$details);
                }else{
                    yt_fcm_push_notification($user_tokens,$details);
                    rz_send_ios_notification($user_tokens,$details['title'].' | '.$details['body']);
                }
            }
        }
    }
}

function rz_get_location_data_by_lat_long($lat, $lon)
{

   // https://maps.googleapis.com/maps/api/geocode/json?latlng=28.737324,77.090981&key=AIzaSyD7BIoSvmyufubmdVEdlb2sTr4waQUexHQ
    // $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&key=AIzaSyD7BIoSvmyufubmdVEdlb2sTr4waQUexHQ";
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&key=AIzaSyA_23OZbQeEKQeLfMBTJ6xd3-hCa33tK4A";
    
    // $url_2 = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=20.254916,76.715059&destinations=20.3127202,76.6839331";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);


  return $response_a;
}


function rz_referral_code($length_of_string=4)
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return strtoupper(substr(str_shuffle($str_result), 0, $length_of_string));
}

if (!function_exists('rz_user_id')) {

    function rz_user_id($in=[])
    {
        if(Auth::user()){
            return Auth::user()->id ? Auth::user()->id : 0;

        }else{
            return 0;
        }
    }
}

if (!function_exists('rz_frequency')) {

    function rz_frequency($skip_days)
    {
        return Frequency::where('skip_days',$skip_days)->first()->name;
    }
}


 function rz_send_ios_notification($device_token,$message_text)
{
    // print_r($device_token);


    @$payload='{"aps":{"alert":"'.$message_text.'","badge":0,"content-available":1,"mutable-content":"1","category" : "myNotificationCategory", "sound":"default"},"sd_type":"genral"}';
    //print_r($payload);die;
    // $payload=[
    //     [
    //         'alert'=>$message_text,
    //         'badge'=>0,
    //         'content-available'=>1,
    //         'mutable-content'=>1,
    //         'category'=>'myNotificationCategory',
    //         'sound'=>'default'
    //     ],
    //     'sd_type'=>'genral'
    // ];

    // $payload=json_encode($payload);
    // print_r( $payload); die;
    //include_once("Cow.pem");


    // $ctx=stream_context_create();

    //     stream_context_set_option($ctx,'ssl','local_cert','/var/www/html/panel/public/pem/rozana.pem');
    //     $fp=stream_socket_client('ssl://gateway.push.apple.com:2195',$err,$errstr,60,STREAM_CLIENT_CONNECT,$ctx);
    // /*end for production*/
    // if($fp)
    // {
    //     if(!empty($device_token)){
    //         foreach ($device_token as $key)
    //         {
    //             try{
    //                 $msg=chr(0).pack("n",32).pack("H*",str_replace(' ','',$key)).pack("n",strlen($payload)).$payload;

    //                 $res=fwrite($fp,$msg);
    //             }catch(Exception $e){

    //             }

    //         }
    //     }


    // }
    // fclose($fp);
    return true;
}


if (!function_exists('send_yt_notification')) {

    function send_yt_notification($details=[],$user_id=0,$data=[],$type='single')
    {
        $msg = yt_notify_msg($details['title'],$details['body'],$data);
        $arr = [
            'user_id'=>$user_id,
            'title'=>$details['title'],
            'body'=>$details['body'],
            'source_type'=>isset($details['source_type']) ? $details['source_type'] : 'other',
            'source_id'=>isset($details['source_id']) ? $details['source_id'] : 0,
            'notification_type'=>$type,
            'data'=>json_encode($data),
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s')
        ];
        DB::table('notifications')->insert($arr);
        // dd($arr);
        if($type == 'single'){
           $device_token = User::where('id',$user_id)->pluck('device_token')->toArray();
            if(!empty($device_token)){
                yt_fcm_push_notification($device_token,$msg);
                rz_send_ios_notification($device_token,$details['title'].' | '.$details['body']);
            }
        }else{
            $user_tokens = User::where('user_type',2)->where('device_token','!=','')->pluck('device_token')->toArray();
            $user_tokens =(array_filter($user_tokens));
            if(!empty($user_tokens)){
                yt_fcm_push_notification($user_tokens,$msg);
                rz_send_ios_notification($user_tokens,$details['title'].' | '.$details['body']);
            }
        }
    }
}

// if (!function_exists('yt_default_alerts')) {

//     function yt_default_alerts($status,$message='')
//     {
//         $alert='';

//         switch ($status) {
//             case 'warning':
//                 echo ' <div class="alert alert-icon-right alert-light-dark mb-4" role="alert">
//                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
//                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
//                 <strong>'.$message.'
//             </div>';
//                 break;
//             case 'success':
//                 echo '<div class="alert alert-success mb-4" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button> <strong>'.$message.' </div>';
//                 break;
//             default:
//                 # code...
//                 break;
//         }
//     }
//     }


// if (!function_exists('send_yatayat_notification')) {

//     function send_yatayat_notification($details=[],$user_id=0,$data=[],$type='single')
//     {
//         $msg = yatayat_notify_msg($details['title'],$details['body'],$data);
//         $arr = [
//             'user_id'=>$user_id,
//             'title'=>$details['title'],
//             'body'=>$details['body'],
//             'source_type'=>isset($details['source_type']) ? $details['source_type'] : 'other',
//             'source_id'=>isset($details['source_id']) ? $details['source_id'] : 0,
//             'notification_type'=>$type,
//             'data'=>json_encode($data),
//             'created_at'=>date('Y-m-d H:i:s'),
//             'updated_at'=>date('Y-m-d H:i:s')
//         ];
//         DB::table('notifications')->insert($arr);
//         // dd($arr);
//         if($type == 'single'){
//            $device_token = User::where('id',$user_id)->pluck('device_token')->toArray();
//            if(!empty($device_token)){
//                 send_fcm_push_notification($device_token,$msg);
//            }
//         }else{
//             $user_tokens = User::where('user_type',2)->where('device_token','!=','')->pluck('device_token')->toArray();
//             $user_tokens =(array_filter($user_tokens));
//             if(!empty($user_tokens)){
//                 send_fcm_push_notification($user_tokens,$msg);
//             }
//         }
//     }
// }


function rz_check_if_delivery_done($order_id,$dt)
{
    # code...
    $date = date('Y-m-d',strtotime($dt));
    $dlvrydone = Delivery::where('order_id',$order_id)->whereDate('created_at',$date)->first();
    if(!$dlvrydone){
        return false;
    }
    return $dlvrydone;
}

function kt_userLastSeen($user_id)
{
    $user = UserLogin::where('user_id',$user_id)->orderBy('id','desc')->first();
    return $user;
}
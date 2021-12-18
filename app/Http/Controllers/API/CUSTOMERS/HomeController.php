<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\FAQ;
use App\Models\Feedback;
use App\Models\Location;
use App\Models\Notification;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Subcategory;
use App\Models\User;
use App\Traits\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    //
    use Delivery;

    public function get_homepage(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            //'user_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id = rz_user_id($input);
        $splashing_text='';
        $settings=Setting::first();
        if($settings){
            $splashing_text=$settings->splashing_text;
        }
        $top_catg_arr=[];
        $categories = Category::active()->orderAsc()->where('show_homepage_top',1)->get();
        foreach ($categories as $k => $v) {
            $v->subcategories = Subcategory::active()->where('category_id',$v->id)->orderAsc()->limit(4)->get();
            $top_catg_arr[]=$v;
        }

        $user = User::where('id',$user_id)->first();

        $top_banners = Banner::active()->topBanner()->orderAsc()->get();
        $bottom_banners = Banner::active()->bottomBanner()->orderAsc()->get();

        $bottom_cat = Category::active()->orderAsc()->where('show_homepage_bottom',1)->get();
        $new_cat_arr = [];
        foreach ($bottom_cat as $k => $v) {
            $v->subcategories = Subcategory::active()->where('category_id',$v->id)->orderAsc()->limit(4)->get();
            $new_cat_arr[]=$v;
        }

        // $whats_new = Product::orderAsc()->whatsnew()->active()->limit(10)->get();
        // $best_offers = Product::orderAsc()->active()->limit(10)->get();
        $today = date('Y-m-d');
        $tomorrow=date('Y-m-d', strtotime(' +1 day'));
        // $today_order=OrderHistory::orderDesc()->active()->whereDate('created_at', $today)->where('user_id',$user_id)->count();
        // $tomorrow_order=OrderHistory::orderDesc()->active()->whereDate('created_at', $tomorrow)->where('user_id',$user_id)->count();

        $today_order=count($this->deliveries_by_date_trait($user_id,$today));
        $tomorrow_order=count($this->deliveries_by_date_trait($user_id,$tomorrow));;

        return yt_api_response([
            'status'=>true,
            'top_banners'=>$top_banners,
            'categories'=>$top_catg_arr,
            'wallet'=>$user ? $user->wallet : 0,
            'splashing_text'=>$splashing_text,
            'todays_orders'=>$today_order,
            'tomorrows_orders'=>$tomorrow_order,
            'bottom_banners'=>$bottom_banners,
            'categories_bottom'=>$new_cat_arr,
            // 'whats_new'=>$whats_new,
            // 'best_offers'=>$best_offers
        ]);
    }

    public function fetch_whatsnew(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            //'user_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $whats_new = Product::orderAsc()->whatsnew()->active()->limit(50)->get();
        return yt_api_response([
            'status'=>true,
            'whats_new'=>$whats_new
        ]);
    }

    public function fetch_bestoffers(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            //'user_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $best_offers = Product::orderAsc()->where('mark_as_new',0)->bestOffers()->active()->limit(50)->get();
        if(!count($best_offers)){
            $best_offers = Product::orderAsc()->where('mark_as_new',0)->active()->limit(50)->get();
        }
        return yt_api_response([
            'status'=>true,
            'best_offers'=>$best_offers
        ]);
    }


    public function check_if_pincode_in_service(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'pincode'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $check=Location::active()->where('pincode',$input['pincode'])->first();
        if($check){
            return yt_api_response([
                'status'=>true,
                'message'=>'',
                'data'=>$check
            ]);
        }
        return yt_api_response([
            'status'=>false,
            'message'=>'You seems to be outside serviceable area please choose other pincodes from the list or try changing the address',
            'data'=>$check
        ]);
    }

    public function fetch_serviceable_pincodes(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            //'user_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $fetch=Location::active()->get();
        return yt_api_response(['status' => true,'data'=>$fetch,'message'=>'fetch']);
    }


    public function get_notifications(Request $request)
    {
        $input = $request->all();
        $user_id = rz_user_id($input);
        $offset = (isset($input['offset']) && !empty($input['offset'])) ? $input['offset'] : 0;
        $limit = $input['limit'];
        $new = [];
        $user = User::where('id',$user_id)->first();
        if($user){
            $user_crt = $user->created_at;
            // Notification::where('user_type',2)->whereDate('created_at', '>=', $user_crt)->whereIn('user_id',[$user_id,0])->update(['read_at'=>date('Y-m-d H:i:s')]);
            $notification = Notification::where('user_type',intval($user->user_type))->where('created_at', '>=', $user_crt)->whereIn('user_id',[$user_id,0])
                ->orderBy('created_at','desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

            return yt_api_response(['status' => true,'limit'=>$limit,'limit_from'=>$offset,'data'=>$notification], 200);
        }

        return yt_api_response(['status' => true,'message'=>'User not found'], 200);
    }


    public function fetch_company_data(Request $request)
    {
        $settings = Setting::first();
        return yt_api_response(['status' => true,'message'=>'',
        'data'=>$settings
        ], 200);
    }


    public function add_feedback(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'experience'=>'required',
            'message'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $input['user_id']=rz_user_id($input);
        $input['status']=1;
        $input['subject']="ROZANA FEEDBACK";


        $create=Feedback::create($input);
        if($create){
            return yt_api_response(['status' => true,
            'message'=>'Feedback sent!',
            'data'=>$create
            ], 200);
        }else{
            return yt_api_response(['status' => false,
            'message'=>'something went wrong!',
            'data'=>$create
            ], 200);
        }
    }


    public function fetch_faqs(Request $request)
    {
        $faqs=FAQ::active()->orderAsc()->get();
        return yt_api_response(['status' => true,
        'message'=>'Fetch!',
        'data'=>$faqs
        ], 200);
    }

}

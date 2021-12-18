<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponApply;
use App\Models\DeliveryCount;
use App\Models\Frequency;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Returns;
use App\Models\Cashback;
use App\Models\CashbackReward;

use App\Models\Vacation;
use App\Traits\CustomMails;
use App\Traits\CustomNotifications;
use App\Traits\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Generator\StringManipulation\Pass\Pass;
use App\Models\Delivery as Deliveries;

class OrderController extends Controller
{
    use Delivery;
    use CustomNotifications;
    use CustomMails;
    public function round_price($price)
    {
        return round(floatval($price));
    }

    public function add_to_cart(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id = rz_user_id($input);

        $check = Cart::where('user_id',$user_id)->where('product_id',$input['product_id'])->buyOnce()->first();
        if($check){
            return response()->json(['status' => false,'message'=>'Already added.']);
        }

        $qty=isset($input['qty']) && intval($input['qty']) > 0 ? $input['qty'] : 1;

        $arr = [
            'user_id'=>$user_id,
            'product_id'=>$input['product_id'],
            'qty'=>$qty,
            'order_type'=>1
        ];
        $create = Cart::create($arr);

        return yt_api_response([
            'status'=>$create ? true : false,
            'message'=>$create ? 'Added!..' : 'something went wrong! Please try again later.',
            'product'=>Product::where('id',$input['product_id'])->first(),
            'data'=>[$create]
        ]);

    }


    public function edit_cart(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'cart_id' => 'required',
            'qty' => 'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id = rz_user_id($input);
        $quantity = $input['qty'];
        $cart=Cart::where('id',$input['cart_id'])->first();
        if($cart){
            $product_id=$cart->product_id;
        }else{
            return yt_api_response(['status' => false,'message'=>'something went wrong.'], 200);
            $product_id=0;
        }
        if($quantity <= 0){
            $delete=$cart->delete();
            if($delete){
                return yt_api_response(['status' => true,'is_delete'=>1,
                'product'=>Product::where('id',$product_id)->first(),
                'message'=>'updated.'], 200);
            }else{
                return yt_api_response(['status' => false,'is_delete'=>0,'message'=>'something went wrong.'], 200);
            }
        }

        $arr = [
            'qty'=>$quantity
        ];
        $update= Cart::where('id',$input['cart_id'])->update($arr);
        if($update){
            return yt_api_response(['status' => true,'is_delete'=>0,
            'product'=>Product::where('id',$product_id)->first(),
            'message'=>'updated.'], 200);
        }
        return yt_api_response(['status' => false,'message'=>'something went wrong.'], 200);
    }

    public function add_to_subscribe(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product_id'=>'required',
            'deliveries'=>'required',
            'start_date'=>'required',
            // 'address_id'=>'required',
            'frequency_id'=>'required',
            'skip_days'=>'required',
            'qty'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id = rz_user_id($input);
        $product_d = Product::where('id',$input['product_id'])->first();
        if($product_d->show_in_subscriptions == 0){
            return yt_api_response(['status' => false,'message'=>'Sorry, Cannot add to subscribe, because its subscription status closed by the system']);
        }
        $delete = Cart::where('user_id',$user_id)->subscribe()->delete();
        $check = Cart::where('user_id',$user_id)->where('product_id',$input['product_id'])->subscribe()->first();
        if($check){
            return response()->json(['status' => false,'message'=>'Already added.']);
        }
        $def_add=Address::where('is_default',1)->where('user_id',$user_id)->first();
        if(!$def_add){
            $def_add=Address::where('user_id',$user_id)->first();
        }

        $arr = [
            'user_id'=>$user_id,
            'product_id'=>$input['product_id'],
            'deliveries'=>$input['deliveries'],
            'start_date'=>date('Y-m-d H:i:s',strtotime($input['start_date'])),
            'address_id'=>isset($input['address_id']) ? $input['address_id'] : 0,
            'frequency_id'=>$input['frequency_id'],
            'skip_days'=>$input['skip_days'],
            'qty'=>$input['qty'],
            'order_type'=>2
        ];
        $create = Cart::create($arr);

        return yt_api_response([
            'status'=>$create ? true : false,
            'message'=>$create ? 'Added!..' : 'something went wrong! Please try again later.',
            'data'=>$create
        ]);

    }

    public function get_deliveries_counts(Request $request)
    {
        $fetch = DeliveryCount::active()->orderBy('total_deliveries','asc')->get();
        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($fetch) ? 'fetch successfully' : 'Sorry! No data found',
            'data'=>$fetch
        ]);
    }

    public function get_cart_items(Request $request)
    {
        $input = $request->all();
        $payment_mode = isset($input['payment_mode']) ? $input['payment_mode'] : 'cod';
        $data=$this->booking_data_arr('buyOnce',$payment_mode);
        return yt_api_response($data);
    }


    public function get_subscribed_items(Request $request)
    {
        $input = $request->all();
        $data=$this->booking_data_arr('subscribe');
        return yt_api_response($data);
    }

    // public function get_cart_items(Request $request)
    // {
    //     $input = $request->all();
    //     $user_id = rz_user_id($input);
    //     $fetch_carts = Cart::where('user_id',$user_id)->get();
    //     $buyOnce = [];
    //     $subscriptions = [];
    //     $total_amount=0;
    //     $subscription_address=[];
    //     if(count($fetch_carts)){
    //         foreach($fetch_carts as $c){
    //             if($c->order_type == 1){
    //                 $c->product = $p= Product::where('id',$c->product_id)->first();
    //                 $c->amount = $amt= $this->round_price(floatval($p ? $p->selling_price : 0)*floatval($c->qty));
    //                 $total_amount += $amt;

    //                 $buyOnce[] = $c;
    //             }elseif($c->order_type == 2){
    //                 $subscription_address=Address::where('id',$c->address_id)->first();
    //                 $c->product = $p= Product::where('id',$c->product_id)->first();
    //                 $c->start_date = date('d M Y',strtotime($c->start_date));
    //                 $c->deliveries_text=$c->deliveries*$c->qty.' '.$p->unit;
    //                 $c->frequency=Frequency::where('id',$c->frequency_id)->first()->name;
    //                 $c->amount = $amt= $this->round_price(floatval($p ? $p->selling_price : 0)*(floatval($c->qty)*$c->deliveries));
    //                 $total_amount += $amt;
    //                 $subscriptions[] = $c;
    //             }
    //         }
    //     }
    //     $check_coupon=CouponApply::where('user_id',$user_id)->where('order_id',0)->first();
    //     if($check_coupon){
    //         $coupon = Coupon::where('id',$check_coupon->coupon_id)->first();
    //         $code=$coupon->name;
    //         $coupon_id=$coupon->id;
    //         if($coupon->type ==1){
    //             $discount = $total_amount*($coupon->discount/100);
    //             $payable_amount=$total_amount-$discount;
    //         }else{
    //             $discount = $coupon->discount;
    //             $payable_amount=$total_amount-$discount;
    //         }

    //     }else{
    //         $discount=0;
    //         $payable_amount=$total_amount;
    //         $code='';
    //         $coupon_id='';
    //     }
    //     // $status=false;
    //     // if(empty($fetch_carts) && empty($subscriptions)){
    //     //     $status = false;
    //     // }else{
    //     //     $status=true;
    //     // }
    //     $def_add=Address::where('is_default',1)->where('user_id',$user_id)->first();
    //     return yt_api_response([
    //         'status'=>Cart::where('user_id',$user_id)->count() ? true : false,
    //         'messsage'=>!empty($fetch_carts) ? 'fetch successfully' : 'Sorry! No data found',
    //         'subtotal'=>$total_amount,
    //         'total_buyonce'=>count($buyOnce),
    //         'total_subscriptions'=>count($subscriptions),
    //         'discount'=>$discount,
    //         'delivery_charges'=>30,
    //         'payable_amount'=>$payable_amount<0 ? 0  : $payable_amount+30,
    //         'coupon_code'=>$code,
    //         'coupon_id'=>$coupon_id,
    //         'buyOnce'=>$buyOnce,
    //         'default_address'=>$def_add?$def_add:[] ,
    //         'subscription_address'=>$subscription_address,
    //         'subscriptions'=>$subscriptions
    //     ]);
    // }

    public function booking_data_arr($type='',$payment_mode='online')
    {
        $user_id = rz_user_id();
        if($type=='subscribe'){
            $fetch_carts = Cart::where('user_id',$user_id)->subscribe()->get();
        }else{
            $fetch_carts = Cart::where('user_id',$user_id)->buyOnce()->get();
        }
       // $fetch_carts = Cart::where('user_id',$user_id)->buyOnce()->get();
        $buyOnce = [];
        $subscriptions = [];
        $total_amount=0;
        $subscription_payable_amount=0;
        $payable_deliveries_amount=0;
        $payable_deliveries_count=5;
        $subscription_address=[];
        $item_price=0;

        $total_deliveries=0;

        $settings=Setting::first();
        if($settings){
            $delivery_chg=round($settings->delivery_charges);
            $payable_deliveries_amount+=$delivery_chg;
        }else{
            $delivery_chg=0;
        }
        $new_del_charge=0;

        if(count($fetch_carts)){
            foreach($fetch_carts as $c){
                if($c->order_type == 1){
                    $c->product = $p= Product::where('id',$c->product_id)->first();
                    $c->amount = $amt= $this->round_price(floatval($p ? $p->selling_price : 0)*floatval($c->qty));
                    $total_amount += $amt;

                    $buyOnce[] = $c;
                }elseif($c->order_type == 2){

                    $ctime=strtotime(date('Y-m-d'));
                    $unix_start_date=strtotime($c->start_date);
                    if($ctime>$unix_start_date){
                        $start_date=date('Y-m-d');
                        $c->start_date=$start_date;
                        $c->save();
                        $start_date=date('d M Y',strtotime($c->start_date));
                    }else{
                        $start_date=date('d M Y',strtotime($c->start_date));
                    }

                    $subscription_address=Address::where('id',$c->address_id)->first();
                    $c->product = $p= Product::where('id',$c->product_id)->first();
                    $c->start_date =$start_date;
                    $c->deliveries_text=$c->deliveries*$c->qty.' x '.$p->unit;
                    $c->frequency=Frequency::where('id',$c->frequency_id)->first()->name;
                    $c->amount = $amt= $this->round_price(floatval($p ? $p->subscription_price : 0)*(floatval($c->qty)*$c->deliveries));
                    $total_amount += $amt;
                    $subscriptions[] = $c;

                    $new_del_charge += $delivery_chg*$c->deliveries;
                    $subscription_payable_amount+=$this->round_price(floatval($p ? $p->subscription_price : 0)*(floatval($c->qty)*$payable_deliveries_count));
                    $payable_deliveries_amount*=$payable_deliveries_count;

                   $item_price=$this->round_price(floatval($p ? $p->subscription_price : 0)*(floatval($c->qty)));
                   $total_deliveries+=$c->deliveries;
                }
            }
        }
        if($new_del_charge<=0){
            $new_del_charge= $delivery_chg;
        }else{
            $delivery_chg=$new_del_charge;
        }

        $check_coupon=CouponApply::where('user_id',$user_id)->orderBy('id','desc')->where('order_id',0)->first();
        if($check_coupon){
            $check_2=$this->check_if_coupon_is_valid($check_coupon->coupon_id,$user_id);
            if($check_2){
                $coupon = Coupon::where('id',$check_coupon->coupon_id)->first();
                $code=$coupon->name;
                $coupon_id=$coupon->id;
                if($coupon->type ==1){
                    $discount = $this->round_price($total_amount*($coupon->discount/100));
                    if(!empty($coupon->max_discount)){
                        if($discount>$this->round_price($coupon->max_discount)){
                            $discount=$coupon->max_discount;
                        }
                    }
                    $payable_amount=$total_amount-$discount;
                }else{
                    $discount=$coupon->discount;
                    if(!empty($coupon->min_order_amount)){
                        if($total_amount < $coupon->min_order_amount){
                            $discount=0;
                            CouponApply::where('user_id',$user_id)->where('order_id',0)->delete();
                        }
                    }
                    if($total_amount > $discount){
                        $payable_amount=$total_amount-$discount;
                    }else{
                        $discount=0;
                        CouponApply::where('user_id',$user_id)->where('order_id',0)->delete();
                        $payable_amount=$total_amount;
                    }


                }
            }else{
                $discount=0;
                $payable_amount=$total_amount;
                $code='';
                $coupon_id='';
            }

        }else{
            $discount=0;
            $payable_amount=$total_amount;
            $code='';
            $coupon_id='';
        }

        if($discount==0){
            $discount=0;
            $payable_amount=$total_amount;
            $code='';
            $coupon_id=0;
        }
        // $status=false;
        // if(empty($fetch_carts) && empty($subscriptions)){
        //     $status = false;
        // }else{
        //     $status=true;
        // }

    

        $def_add=Address::where('is_default',1)->where('user_id',$user_id)->first();
        if(!$def_add){
            $def_add=Address::where('user_id',$user_id)->first();
        }

        $new_payable_deliveries_amount=$subsdel=$this->round_price($payable_deliveries_amount);
        $subs_pay=0;

        // if(!empty($discount)){
        //     $new_payable_deliveries_amount-=$discount;
        // }
        $new_dis=$discount;

        if($item_price>100){
            $discount+=$new_payable_deliveries_amount;
          //  $payable_amount=$total_amount-$discount;
           // $subscription_payable_amount-=$discount;
           $subsdel=0;
           $delivery_chg=0;
        }
        if($type=='subscribe'){
            $crt_fill=Cart::where('user_id',$user_id)->subscribe()->count();
        }else{
            $crt_fill=Cart::where('user_id',$user_id)->buyOnce()->count();
        }
        $user = User::where('id',$user_id)->first();

        $cashback_disc_perct=0;
        $cashback_discount=0;
        $cashback_reward_perct=0;
        $cashback_reward=0;
        if($payment_mode != 'cod' && $type!='subscribe' && $payment_mode != ''){
            $cshbck = Cashback::where('amount','<=',$payable_amount)->first();
            if($cshbck){
                $cashback_wallet =  floatval($user->cashback_wallet);
                $cashback_disc_perct = intval($cshbck->cashback_perct); /***cashback wallet percentage can use */
                $cashback_discount = round($cashback_wallet*($cashback_disc_perct/100));

                if(intval($user->cashback_wallet)){
                    $payable_amount -= $cashback_discount;

                }
            }

            if($payable_amount>=1000){
                $cashback_reward_perct=10;
                $cashback_reward=round($payable_amount*($cashback_reward_perct/100));
            }
        }

        

        return [
            'status'=>$crt_fill ? true : false,
            'messsage'=>($crt_fill) ? 'fetch successfully' : 'Sorry! No data found',
            'subtotal'=>$total_amount,

            'wallet_balance'=>$user->wallet,
            'cashback_wallet_balance'=>$user->cashback_wallet,

            'cashback_disc_perct'=>$cashback_disc_perct,
            'cashback_discount'=>$cashback_discount,

            'cashback_reward_perct'=>$cashback_reward_perct,
            'cashback_reward'=>$cashback_reward,

            'total_buyonce'=>count($buyOnce),
            'total_subscriptions'=>count($subscriptions),
            'discount'=>$discount,
            'delivery_charges'=>$delivery_chg,
            'payable_deliveries_count'=>$payable_deliveries_count,
            'payable_deliveries_amount'=>$new_payable_deliveries_amount,
            'subscription_payable_amount'=>$type=='subscribe'? $subs_pay=$this->round_price($subscription_payable_amount-$new_dis):$payable_amount+$delivery_chg ,
            'net_subscription_payable_amount'=>$subs_pay+$subsdel,

            'payable_amount'=>$payable_amount<0 ? 0+$delivery_chg  : $payable_amount+$delivery_chg,
            'coupon_code'=>$code,
            'coupon_id'=>$coupon_id,
            'buyOnce'=>$buyOnce,
            'default_address'=>$def_add?$def_add:[] ,
            'subscription_address'=>$subscription_address,
            'subscriptions'=>$subscriptions,
            'cart'=>$fetch_carts
        ];
        //
    }

    public function count_cart_items(Request $request)
    {
        $input=$request->all();
        $user_id=rz_user_id($input);
        $count=Cart::where('user_id',$user_id)->buyOnce()->count();
        return yt_api_response([
            'status'=>true,
            'count'=>$count
        ]);
    }

    public function create_order(Request $request)
    {
        $input=$request->all();
        $shipping_name='';
        $shipping_phone='';
        $shipping_flat='';
        $shipping_pincode='';
        $shipping_location='';
        $shipping_lat='';
        $shipping_lng='';
        $inv_ids=[];

        $validator = Validator::make($input, [
            'payment_mode'=>'required',
            //'type'=>'required'
            // 'payable_amount'=>'required',
            // 'subtotal'=>'required',
            // 'discount'=>'required',
            // 'gst'=>'required',
            // 'delivery_charges'=>'required',
            // 'address_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id($input);

        $order_history=[];
        $order=[];

        //$type=isset($input['type'])?$input['type']:'';
        $booking_data_arr=$this->booking_data_arr('buyOnce',$input['payment_mode']);
        if($booking_data_arr['status']==false){
            return yt_api_response(['status'=>false,'message'=>'no cart items found']);
        }

        $get_cart_items=$booking_data_arr['cart'];

        if($address=$booking_data_arr['default_address']){
            $shipping_name=$address->name;
            $shipping_phone=$address->phone;
            $shipping_flat=$address->flat;
            $shipping_pincode=$address->pincode;
            $shipping_location=$address->location;
            $shipping_lat=$address->lat;
            $shipping_lng=$address->lng;
        }
        $subtotal=floatval($booking_data_arr['subtotal']);
        $coupon_discount = $discount=floatval($booking_data_arr['discount']);
        $cashback_discount = floatval($booking_data_arr['cashback_discount']);
        $discount += $cashback_discount;
        $payable_amount=floatval($booking_data_arr['payable_amount']);
        $delivery_charges=floatval($booking_data_arr['delivery_charges']);

        $cashback_reward =  floatval($booking_data_arr['cashback_reward']);
        $coupon_id=intval($booking_data_arr['coupon_id']);


        $delivery_date=date('Y-m-d');
        if (intval(date('H')) <= 17) {
            $delivery_date=date('Y-m-d').' 19:00:00';
        }else{
            $delivery_date=date('Y-m-d',strtotime("+1 days")).' 07:00:00';
        }

        $order=[
            'user_id'=>$user_id,
            'subtotal'=>$subtotal,
            'discount'=>$discount,
            'coupon_discount'=>$coupon_discount,
            'cashback_discount'=>$cashback_discount,
            'payable_amount'=>$payable_amount,
            'coupon_id'=>($coupon_id),
            'shipping_name'=>$shipping_name,
            'shipping_phone'=>$shipping_phone,
            'shipping_flat'=>$shipping_flat,
            'shipping_pincode'=>$shipping_pincode,
            'shipping_location'=>$shipping_location,
            'lat'=>$shipping_lat,
            'lng'=>$shipping_lng,
            'delivery_charges'=>$delivery_charges,
            'delivery_date'=>$delivery_date,
            'payment_mode'=>isset($input['payment_mode']) ? $input['payment_mode'] : 'cod' ,
            'payable_deliveries_count'=>1,
            'paid_amount'=>$payable_amount,
            'order_type'=>1,
        ];
        DB::beginTransaction();
        try {



            $ord=Order::create($order);
            $datetime=date('Y-m-d H:i:s');
            foreach($get_cart_items as $c){
                $p= Product::where('id',$c->product_id)->first();
                $old_stock=intval($p->stock);
                $s_qty=$c->qty;

                $update_stock=$old_stock-$s_qty;
                if($update_stock<0){
                    $update_stock=0;
                }
                $new=[
                    'product_id'=>$c->product_id,
                    'user_id'=>$user_id,
                    'stock'=>$c->qty,
                    'stock_status'=>2,
                    'status'=>2,
                    'added_by'=>0
                ];
                $inventory=Inventory::create($new);
                $inv_ids[]=$inventory->id;

                $p->stock=$update_stock;
                $p->save();
                if($c->order_type==2){
                    $skip_days=$c->skip_days;
                    $deliveries=$c->deliveries;
                    $skip_dates='';
                    $non_skip_dates='';
                    if($skip_days != 0){
                        $additional_days=round($deliveries/$skip_days);
                    }else{
                        $additional_days=0;
                    }
                    $total_days=$additional_days+$deliveries-1;
                    if($total_days<0){
                        $total_days=0;
                    }
                    $end_date=date('Y-m-d H:i:s', strtotime($c->start_date. ' + '.intval($total_days).' days'));

                    $dates_arr=rz_getDatesFromRange($c->start_date,$end_date);
                    if($skip_days != 0){
                        $skip_dates_arr=[];
                        $non_skip_dates_arr=[];
                        $i = 0;
                        foreach($dates_arr as $value) {
                            if ($i++ % ($skip_days+1) == 0) {
                                $non_skip_dates_arr[] = $value;
                            }else{
                                $skip_dates_arr[]=$value;
                            }
                        }
                        $skip_dates=implode('|',$skip_dates_arr);
                        $non_skip_dates=implode('|',$non_skip_dates_arr);
                    }else{
                        $non_skip_dates=implode('|',$dates_arr);
                    }
                }else{
                    $skip_dates='';
                    $non_skip_dates=$c->start_date;
                    $end_date=date('Y-m-d')." 00:00:00";
                }

                $order_history[]=[
                    'user_id'=>$user_id,
                    'order_id'=>$ord->id,
                    'product_id'=>$c->product_id,
                    'order_type'=>$c->order_type,
                    'deliveries'=>$c->deliveries,
                    'start_date'=>date('Y-m-d H:i:s',strtotime($c->start_date)),
                    'skip_dates'=>$skip_dates,
                    'non_skip_dates'=>$non_skip_dates,
                    'shipping_name'=>$shipping_name,
                    'shipping_phone'=>$shipping_phone,
                    'shipping_flat'=>$shipping_flat,
                    'shipping_pincode'=>$shipping_pincode,
                    'shipping_location'=>$shipping_location,
                    'skip_days'=>$c->skip_days,
                    'qty'=>$c->qty,
                    'price'=>$this->round_price(floatval($p->selling_price)*floatval($c->qty)*($c->order_type==2?floatval($c->deliveries):1)),
                    'end_date'=>$end_date,
                    'delivery_date'=>$delivery_date,
                    'actual_qty'=>$p->qty,
                    'unit'=>$p->unit,
                    'created_at'=>$datetime,
                    'updated_at'=>$datetime
                ];
            }
            OrderHistory::insert($order_history);
            Cart::where('user_id',$user_id)->delete();
            if($input['payment_mode']=='online'){
                $txn=[
                    'user_id'=>$user_id,
                    'txn_name'=>'Payment for Order #'.$ord->id,
                    'payment_mode'=>'online',
                    'txn_for'=>'order',
                    'type'=>'debit',
                    'old_wallet'=>0,
                    'txn_amount'=>$payable_amount,
                    'update_wallet'=>0,
                    'status'=>1,
                    'txn_mode'=>'other',
                    'order_id'=>$ord->id,
                    'order_txn_id'=>$input['txn_id'],
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $transaction = Transaction::create($txn);
                $ord->txn_id=$transaction->id;
                $ord->is_paid=1;
                $ord->save();
            }elseif($input['payment_mode']=='wallet'){
                $user=User::where('id',$user_id)->first();
                $old_wallet=floatval($user->wallet);
                if($payable_amount > $old_wallet){
                    DB::rollback();
                    return yt_api_response([
                        'status'=>false,
                        'message'=>'your wallet amount is less than payable amount.Please recharge first to proceed',
                        'data'=>null
                    ]);
                }
                $updated_wallet = $old_wallet - $payable_amount;
                $user->wallet=$updated_wallet;
                $user->save();
                $txn=[
                    'user_id'=>$user_id,
                    'txn_name'=>'Payment for Order #'.$ord->id,
                    'payment_mode'=>'wallet',
                    'txn_for'=>'order',
                    'type'=>'debit',
                    'old_wallet'=>$old_wallet,
                    'txn_amount'=>$payable_amount,
                    'update_wallet'=>$updated_wallet,
                    'status'=>1,
                    'txn_mode'=>'other',
                    'order_id'=>$ord->id,
                    'order_txn_id'=>$input['txn_id'].$user_id,
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $transaction = Transaction::create($txn);
                $ord->txn_id=$transaction->id;
                $ord->is_paid=1;
                $ord->save();
            }
            if(intval($cashback_discount)){
                $old_cashback_wallet=floatval($user->cashback_wallet);
                $debit_amount = $cashback_discount;
                if($debit_amount<=$old_cashback_wallet){
                    $update_cashback_wallet = $old_cashback_wallet - $debit_amount;
                    if($update_cashback_wallet<0){$update_cashback_wallet=0;}
                    $user->cashback_wallet=$update_cashback_wallet;
                    $user->save();
                    $txn=[
                        'user_id'=>$user_id,
                        'txn_name'=>'Payment for Order #'.$ord->id,
                        'payment_mode'=>'cashback_wallet',
                        'txn_for'=>'order',
                        'type'=>'debit',
                        'old_wallet'=>$old_cashback_wallet,
                        'txn_amount'=>$debit_amount,
                        'update_wallet'=>$update_cashback_wallet,
                        'status'=>1,
                        'txn_mode'=>'other',
                        'order_id'=>$ord->id,
                        'order_txn_id'=>time().$user_id,
                        'created_at'=>$date=date('Y-m-d H:i:s'),
                        'updated_at'=>$date,
                        'wallet_type'=>2
                    ];
                    Transaction::create($txn);
                }
               
                
            }

            if(intval($cashback_reward)){
                $old_cashback_wallet=floatval($user->cashback_wallet);
                $credit_amount = $cashback_reward;
                $update_cashback_wallet = $old_cashback_wallet + $credit_amount;
                if($update_cashback_wallet<0){$update_cashback_wallet=0;}
                $user->cashback_wallet=$update_cashback_wallet;
                $user->save();
                $txn=[
                    'user_id'=>$user_id,
                    'txn_name'=>'Rs.'.$credit_amount.' Cashback for Order #'.$ord->id,
                    'payment_mode'=>'cashback_wallet',
                    'txn_for'=>'order',
                    'type'=>'credit',
                    'old_wallet'=>$old_cashback_wallet,
                    'txn_amount'=>$credit_amount,
                    'update_wallet'=>$update_cashback_wallet,
                    'status'=>1,
                    'txn_mode'=>'other',
                    'order_id'=>$ord->id,
                    'order_txn_id'=>uniqid().$user_id,
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date,
                    'wallet_type'=>2
                ];
                Transaction::create($txn);
            
               
                
            }
            
            if($coupon_id){
                CouponApply::where('user_id',$user_id)->where('order_id',0)->delete();
            }
            if(!empty($inv_ids)){
                Inventory::whereIn('id',$inv_ids)->update(['order_id'=>$ord->id]);
            }

            $delivery_boy=User::deliveryBoy()->where('is_online',1)->where('pincode',$shipping_pincode)->first();
            if($delivery_boy){
                $ord->delivery_boy_id=$delivery_id=$delivery_boy->id;
                $ord->deliver_boy_subscription_id=$delivery_boy->id;
                $ord->status=1;
                $ord->save();
            }
            DB::commit();

            // $this->create_order_mail($ord->id);
            $this->create_order_notification(Auth::user(),$ord->id);
            if(isset($delivery_id)){
                $this->assign_deliveryboy_notification(Auth::user(),$ord);
            }
            return yt_api_response([
                'status'=>true,
                'message'=>'Order done successfully',
                'data'=>$ord
            ]);
        }catch (\Exception $e) {
            DB::rollback();
            return yt_api_response([
                'status'=>false,
                'message'=>'Something went wrong. '.$e,
                'data'=>null
            ]);
        }

    }



    public function create_subscription_order(Request $request)
    {
        $input=$request->all();
        $shipping_name='';
        $shipping_phone='';
        $shipping_flat='';
        $shipping_pincode='';
        $shipping_location='';
        $shipping_lat='';
        $shipping_lng='';
        $inv_ids=[];

        $validator = Validator::make($input, [
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id($input);

        $order_history=[];
        $order=[];

        $booking_data_arr=$this->booking_data_arr('subscribe');
        if(empty($booking_data_arr['subscriptions'])){
            return yt_api_response(['status'=>false,'message'=>'no cart items found']);
        }

        $get_cart_items=$booking_data_arr['cart'];

        if($address=$booking_data_arr['default_address']){
            $shipping_name=$address->name;
            $shipping_phone=$address->phone;
            $shipping_flat=$address->flat;
            $shipping_pincode=$address->pincode;
            $shipping_location=$address->location;
            $shipping_lat=$address->lat;
            $shipping_lng=$address->lng;
        }
        $subtotal=floatval($booking_data_arr['subtotal']);
        $discount=floatval($booking_data_arr['discount']);
        $payable_amount=floatval($booking_data_arr['payable_amount']);
        $delivery_charges=floatval($booking_data_arr['delivery_charges']);

        $coupon_id=intval($booking_data_arr['coupon_id']);


        $delivery_date=date('Y-m-d');
        if (intval(date('H')) <= 17) {
            $delivery_date=date('Y-m-d').' 19:00:00';
        }else{
            $delivery_date=date('Y-m-d',strtotime("+1 days")).' 07:00:00';
        }

        $order=[
            'user_id'=>$user_id,
            'subtotal'=>$subtotal,
            'discount'=>$discount,
            'payable_amount'=>$payable_amount,
            'coupon_id'=>($coupon_id),
            'shipping_name'=>$shipping_name,
            'shipping_phone'=>$shipping_phone,
            'shipping_flat'=>$shipping_flat,
            'shipping_pincode'=>$shipping_pincode,
            'shipping_location'=>$shipping_location,
            'lat'=>$shipping_lat,
            'lng'=>$shipping_lng,
            'delivery_charges'=>$delivery_charges,
            'delivery_date'=>$delivery_date,
            'payment_mode'=>isset($input['payment_mode']) ? $input['payment_mode'] : 'cod' ,

            'payable_deliveries_count'=>$booking_data_arr['payable_deliveries_count'],
            'paid_amount'=>$booking_data_arr['net_subscription_payable_amount'],
            'order_type'=>2,
        ];
        // DB::beginTransaction();
        // try {



            $ord=Order::create($order);
            $datetime=date('Y-m-d H:i:s');
            foreach($get_cart_items as $c){
                $p= Product::where('id',$c->product_id)->first();
                $old_stock=intval($p->stock);
                $s_qty=$c->qty;

                $update_stock=$old_stock-$s_qty;
                if($update_stock<0){
                    $update_stock=0;
                }
                $new=[
                    'product_id'=>$c->product_id,
                    'user_id'=>$user_id,
                    'stock'=>$c->qty,
                    'stock_status'=>2,
                    'status'=>2,
                    'added_by'=>0
                ];
                $inventory=Inventory::create($new);
                $inv_ids[]=$inventory->id;

                $p->stock=$update_stock;
                $p->save();
                if($c->order_type==2){
                    $skip_days=$c->skip_days;
                    $deliveries=$c->deliveries;
                    $skip_dates='';
                    $non_skip_dates='';
                    if($skip_days != 0){
                        $additional_days=round($deliveries/$skip_days);
                    }else{
                        $additional_days=0;
                    }
                    $total_days=$additional_days+$deliveries-1;
                    if($total_days<0){
                        $total_days=0;
                    }
                    $end_date=date('Y-m-d H:i:s', strtotime($c->start_date. ' + '.intval($total_days).' days'));

                    $dates_arr=rz_getDatesFromRange($c->start_date,$end_date);
                    if($skip_days != 0){
                        $skip_dates_arr=[];
                        $non_skip_dates_arr=[];
                        $i = 0;
                        foreach($dates_arr as $value) {
                            if ($i++ % ($skip_days+1) == 0) {
                                $non_skip_dates_arr[] = $value;
                            }else{
                                $skip_dates_arr[]=$value;
                            }
                        }
                        $skip_dates=implode('|',$skip_dates_arr);
                        $non_skip_dates=implode('|',$non_skip_dates_arr);
                    }else{
                        $non_skip_dates=implode('|',$dates_arr);
                    }
                }else{
                    $skip_dates='';
                    $non_skip_dates=$c->start_date;
                    $end_date=date('Y-m-d')." 00:00:00";
                }

                $order_history[]=[
                    'user_id'=>$user_id,
                    'order_id'=>$ord->id,
                    'product_id'=>$c->product_id,
                    'order_type'=>$c->order_type,
                    'deliveries'=>$c->deliveries,
                    'start_date'=>date('Y-m-d H:i:s',strtotime($c->start_date)),
                    'skip_dates'=>$skip_dates,
                    'non_skip_dates'=>$non_skip_dates,
                    'shipping_name'=>$shipping_name,
                    'shipping_phone'=>$shipping_phone,
                    'shipping_flat'=>$shipping_flat,
                    'shipping_pincode'=>$shipping_pincode,
                    'shipping_location'=>$shipping_location,
                    'skip_days'=>$c->skip_days,
                    'qty'=>$c->qty,
                    'price'=>$this->round_price(floatval($p->subscription_price)*floatval($c->qty)*(floatval($c->deliveries))),
                    'end_date'=>$end_date,
                    'delivery_date'=>$delivery_date,
                    'actual_qty'=>$p->qty,
                    'unit'=>$p->unit,
                    'created_at'=>$datetime,
                    'updated_at'=>$datetime
                ];
            }
            OrderHistory::insert($order_history);
            Cart::where('user_id',$user_id)->delete();
            $user=User::where('id',$user_id)->first();
            $old_wallet=floatval($user->wallet);
            $payable_amount= $ord->paid_amount;
            if($payable_amount > $old_wallet){
                DB::rollback();
                return yt_api_response([
                    'status'=>false,
                    'message'=>'your wallet amount is less than payable amount.Please recharge first to proceed',
                    'data'=>null
                ]);
            }
            $updated_wallet = $old_wallet - $payable_amount;
            $user->wallet=$updated_wallet;
            $user->save();
            $txn=[
                'user_id'=>$user_id,
                'payment_mode'=>'wallet',
                'txn_for'=>'order',
                'type'=>'debit',
                'old_wallet'=>$old_wallet,
                'txn_amount'=>$payable_amount,
                'update_wallet'=>$updated_wallet,
                'status'=>1,
                'txn_mode'=>'other',
                'order_id'=>$ord->id,
                'order_txn_id'=>time().$user_id,
                'created_at'=>$date=date('Y-m-d H:i:s'),
                'updated_at'=>$date
            ];
            $transaction = Transaction::create($txn);
            $ord->txn_id=$transaction->id;
            $ord->is_paid=1;
            $ord->save();

            if($coupon_id){
                CouponApply::where('user_id',$user_id)->where('order_id',0)->delete();
            }
            if(!empty($inv_ids)){
                Inventory::whereIn('id',$inv_ids)->update(['order_id'=>$ord->id]);
            }

            $delivery_boy=User::deliveryBoy()->where('is_online',1)->where('pincode',$shipping_pincode)->first();
            if($delivery_boy){
                $ord->delivery_boy_id=$delivery_boy->id;
                $ord->deliver_boy_subscription_id=$delivery_id=$delivery_boy->id;
                $ord->status=1;
                $ord->save();
            }
           // DB::commit();
            $this->create_order_notification(Auth::user(),$ord->id);
            // $this->create_order_mail($ord->id);
            if(isset($delivery_id)){
                $this->assign_deliveryboy_notification(Auth::user(),$ord);
            }

            return yt_api_response([
                'status'=>true,
                'message'=>'Order done successfully',
                'data'=>$ord
            ]);
        // }catch (\Exception $e) {
        //     DB::rollback();
        //     return yt_api_response([
        //         'status'=>false,
        //         'message'=>'Something went wrong.',
        //         'data'=>null
        //     ]);
        // }

    }

    public function cancel_order(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
         'order_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id();
        $order=Order::where('id',$input['order_id'])->where('user_id',$user_id)->first();
        $deliveries=Deliveries::where('order_id',$input['order_id'])->count();
        if($order){
            DB::beginTransaction();
            $user=User::where('id',$user_id)->first();
            $old_wallet=$user->wallet;
            try {
            $order->status=3;
            $order->save();
            if($order->payment_mode=='wallet' || $order->payment_mode=='online'){
                $refund_amount=0;
                if($order->order_type==2){
                    if($deliveries<$order->payable_deliveries_count){
                        $refund_deliveries=$order->payable_deliveries_count-$deliveries;
                        $refund_amount=round($order->paid_amount/$refund_deliveries);
                    }
                }else{
                    $refund_amount=$order->paid_amount;
                }

              //  $refund_amount=$order->payable_amount;
                $updated_wallet = $old_wallet + $refund_amount;
                $user->wallet=$updated_wallet;
                $user->save();

                $txn=[
                    'user_id'=>$user->id,
                    'payment_mode'=>'wallet',
                    'order_id'=>$order->id,
                    'order_txn_id'=>time().$order->id.$user->id,
                    'type'=>'credit',
                    'old_wallet'=>$old_wallet,
                    'txn_amount'=>$refund_amount,
                    'update_wallet'=>$updated_wallet,
                    'status'=>1,
                    'txn_for'=>'refund',
                    'txn_mode'=>'other',
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $transaction = Transaction::create($txn);
                $order->is_refunded=$transaction->id;
                $order->save();

            }
            $o_histories=OrderHistory::where('order_id',$order->id)->get();
            if(count($o_histories)){
                foreach($o_histories as $c){
                    $p= Product::where('id',$c->product_id)->first();
                    $old_stock=intval($p->stock);
                    $s_qty=$c->qty;
                    $update_stock=$old_stock+$s_qty;
                    if($update_stock<0){
                        $update_stock=0;
                    }
                    $p->stock=$update_stock;
                    $p->save();
                }
            }
            $order_history=OrderHistory::where('order_id',$order->id)->update(['status'=>3]);
            DB::commit();
            if(isset($refund_amount) && !empty($refund_amount)){
                // $msg="You have got your ";
                $this->wallet_txn_notification($user,$refund_amount,'credit');
            }

            return yt_api_response([
                'status'=>true,
                'message'=>'Cancelled Successfully.',
                'data'=>$order
            ]);
            }catch (\Exception $e) {
                DB::rollback();
                return yt_api_response([
                    'status'=>false,
                    'message'=>'Something went wrong.',
                    'data'=>null
                ]);
            }
        }

        return yt_api_response([
            'status'=>false,
            'message'=>'Something went wrong.',
            'data'=>null
        ]);
    }

    // public function create_order(Request $request)
    // {
    //     $input=$request->all();
    //     $shipping_name='';
    //     $shipping_phone='';
    //     $shipping_flat='';
    //     $shipping_pincode='';
    //     $shipping_location='';
    //     $shipping_lat='';
    //     $shipping_lng='';

    //     $validator = Validator::make($input, [
    //         'coupon_id'=>'required',
    //         'payable_amount'=>'required',
    //         'subtotal'=>'required',
    //         'discount'=>'required',
    //         'gst'=>'required',
    //         'delivery_charges'=>'required',
    //         'address_id'=>'required'
    //     ]);
    //     if ($validator->fails()) {
    //         $message = yt_validator_error_messages($validator);
    //         return yt_api_response(['status' => false,'message'=>$message]);
    //     }
    //     $user_id=rz_user_id($input);
    //     $get_cart_items=Cart::where('user_id',$user_id)->get();
    //     $get_cart_items_count=Cart::where('user_id',$user_id)->count();
    //     if(!$get_cart_items_count){
    //         return yt_api_response(['status'=>false,'message'=>'no cart items found']);
    //     }

    //     $order_history=[];
    //     $order=[];
    //     $address = Address::where('id',$input['address_id'])->first();
    //     $shipping_name=$address->name;
    //     $shipping_phone=$address->phone;
    //     $shipping_flat=$address->flat;
    //     $shipping_pincode=$address->pincode;
    //     $shipping_location=$address->location;
    //     $shipping_lat=$address->lat;
    //     $shipping_lng=$address->lng;

    //     $order=[
    //         'user_id'=>$user_id,
    //         'subtotal'=>floatval($input['subtotal']),
    //         'discount'=>floatval($input['discount']),
    //         'payable_amount'=>floatval($input['payable_amount']),
    //         'coupon_id'=>$input['coupon_id'],
    //         'shipping_name'=>$shipping_name,
    //         'shipping_phone'=>$shipping_phone,
    //         'shipping_flat'=>$shipping_flat,
    //         'shipping_pincode'=>$shipping_pincode,
    //         'shipping_location'=>$shipping_location,
    //         'lat'=>$shipping_lat,
    //         'lng'=>$shipping_lng,
    //         'payment_mode'=>$input['payment_mode'],
    //     ];
    //     DB::beginTransaction();
    //     try {
    //         $ord=Order::create($order);
    //         $datetime=date('Y-m-d H:i:s');
    //         foreach($get_cart_items as $c){
    //             $p= Product::where('id',$c->product_id)->first();
    //             $order_history[]=[
    //                 'user_id'=>$user_id,
    //                 'order_id'=>$ord->id,
    //                 'product_id'=>$c->product_id,
    //                 'order_type'=>$c->order_type,
    //                 'deliveries'=>$c->deliveries,
    //                 'start_date'=>$c->start_date,
    //                 'shipping_name'=>$c->shipping_name ? $c->shipping_name :$shipping_name,
    //                 'shipping_phone'=>$c->shipping_phone ? $c->shipping_phone :$shipping_phone,
    //                 'shipping_flat'=>$c->shipping_flat ? $c->shipping_flat :$shipping_flat,
    //                 'shipping_pincode'=>$c->shipping_pincode ? $c->shipping_pincode :$shipping_pincode,
    //                 'shipping_location'=>$c->shipping_location ? $c->shipping_location :$shipping_location,
    //                 'skip_days'=>$c->skip_days,
    //                 'qty'=>$c->qty,
    //                 'price'=>$this->round_price(floatval($p->selling_price)*floatval($c->qty)),
    //                 'created_at'=>$datetime,
    //                 'updated_at'=>$datetime
    //             ];
    //         }
    //         OrderHistory::insert($order_history);
    //         Cart::where('user_id',$user_id)->delete();
    //         DB::commit();

    //         return yt_api_response([
    //             'status'=>true,
    //             'message'=>'Order done successfully',
    //             'data'=>$ord
    //         ]);
    //     }catch (\Exception $e) {
    //         DB::rollback();
    //         return yt_api_response([
    //             'status'=>false,
    //             'message'=>'Something went wrong.',
    //             'data'=>null
    //         ]);
    //     }

    // }

    public function get_coupons(Request $request)
    {
        $input=$request->all();
        $user_id=rz_user_id($input);
        $fetch=Coupon::active()->orderAsc()->get();
        $new=[];
        foreach($fetch as $f){
            $check_valid=$this->check_if_coupon_is_valid($f->id,$user_id);
            if($check_valid){
                $new[]=$f;
            }
        }
        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($new) ? 'fetch successfully' : 'Sorry! No data found',
            'data'=>$new
        ]);
    }

    public function check_if_coupon_is_valid($coupon_id=0,$user_id=0)
    {
        $check=Coupon::where('id',$coupon_id)->first();
        if(!$check){
            return false;
        }
        $time=time();
        $expires_on=strtotime($check->expires_on);
        $starts_on=strtotime($check->starts_on);

        if($time>$expires_on){
            return false;
        }
        if($time<$starts_on){
            return false;
        }
        $count=Order::where('coupon_id',$check->id)->where('user_id',$user_id)->whereNotIn('status',[3,4])->count();
        if($count >= $check->use_limit){
            return false;
        }

        return true;
    }
    public function apply_coupon(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'coupon_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id($input);

        $delete=CouponApply::where('user_id',$user_id)->where('order_id',0)->delete();
        $check_valid=$this->check_if_coupon_is_valid($input['coupon_id'],$user_id);
        // if($check){
        //     return yt_api_response([
        //         'status'=>true,
        //         'message'=>'apply successfully',
        //         'data'=>$check
        //     ]);
        // }
        if($check_valid){
            $arr=[
                'user_id'=>$user_id,
                'order_id'=>0,
                'coupon_id'=>$input['coupon_id']
            ];
            $create=CouponApply::create($arr);
            if($create){
                return yt_api_response([
                    'status'=>true,
                    'message'=>'apply successfully',
                    'data'=>$create
                ]);
            }
        }

        return yt_api_response([
            'status'=>false,
            'message'=>'something went wrong',
            'data'=>$create
        ]);
    }

    public function remove_coupon(Request $request)
    {
        $input=$request->all();
        $user_id=rz_user_id($input);
        $remove=CouponApply::where('user_id',$user_id)->where('order_id',0)->delete();
        if($remove){
            return yt_api_response([
                'status'=>true,
                'message'=>'remove successfully',
                'data'=>$remove
            ]);
        }

        return yt_api_response([
            'status'=>false,
            'message'=>'something went wrong.',
            'data'=>0
        ]);
    }


    public function my_subscriptions(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'limit'=>'required',
            'offset'=>'required'
        ]);
        $limit=isset($input['limit']) ? intval($input['limit']) : 10;
        $offset=isset($input['offset']) ? intval($input['offset']) : 0;

        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id($input);
        $orders=OrderHistory::orderDesc()->subscriptions()->where('user_id',$user_id)->limit($limit)->offset($offset)->get();
        $new=[];
        foreach($orders as $order){
            $product=Product::where('id',$order->product_id)->first();
            $order->order_type=  $order->order_type==1?'buyOnce':'subscribe';
            $order->product=$product;
            $new[]=$order;
        }
        return yt_api_response([
            'status'=>true,
            'message'=>'fetch.',
            'data'=>$new
        ]);
    }

    public function fetch_orders(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'date'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $date=$input['date'];
        $user_id=rz_user_id($input);
        $new = $this->deliveries_by_date_trait($user_id,$date);
        return yt_api_response([
            'status'=>true,
            'message'=>'fetch.',
            'data'=>$new
        ]);
    }


    // public function fetch_orders(Request $request)
    // {
    //     $input=$request->all();
    //     $validator = Validator::make($input, [
    //         'date'=>'required'
    //     ]);
    //     if ($validator->fails()) {
    //         $message = yt_validator_error_messages($validator);
    //         return yt_api_response(['status' => false,'message'=>$message]);
    //     }
    //     $date=$input['date'];
    //     $user_id=rz_user_id($input);
    //     $orders=OrderHistory::orderDesc()->buyOnce()->whereDate('created_at', $date)->where('user_id',$user_id)->get();
    //     $new=[];
    //     foreach($orders as $order){
    //         if($order->order_type == 2){
    //             if(rz_have_vacation($user_id,$date)){
    //                 continue;
    //             }
    //         }

    //         $product=Product::where('id',$order->product_id)->first();
    //         $order->order_type=  $order->order_type==1?'buyOnce':'subscribe';
    //         $order->product=$product;
    //         $new[]=$order;
    //     }

    //     $orders2=OrderHistory::orderDesc()->subscriptions()->where('user_id',$user_id)->get();
    //     $new=[];
    //     foreach($orders2 as $order){
    //         if($order->order_type == 2){
    //             if(rz_have_vacation($user_id,$date)){
    //                 continue;
    //             }
    //         }

    //         $product=Product::where('id',$order->product_id)->first();
    //         $order->order_type=  $order->order_type==1?'buyOnce':'subscribe';
    //         $order->product=$product;
    //         $new[]=$order;
    //     }
    //     return yt_api_response([
    //         'status'=>true,
    //         'message'=>'fetch.',
    //         'data'=>$new
    //     ]);
    // }

    public function change_subscription_address(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'address_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $update = OrderHistory::where('user_id',rz_user_id($input))->update(['address_id'=>$input['address_id']]);

        return yt_api_response([
            'status'=>true,
            'message'=>'updated.',
        ]);
    }


    public function my_orders(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'type'=>'required',
            // 'limit'=>'required',
            // 'offset'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $limit=isset($input['limit']) ? intval($input['limit']) : 10;
        $offset=isset($input['offset']) ? intval($input['offset']) : 0;

        $user_id=rz_user_id($input);
        $type=$input['type'];
        if($type=='upcoming'){
            $orders=Order::orderDesc()->whereIn('status',[0,1])->where('user_id',$user_id)
            ->buyOnce()
            ->offset($offset)
            ->limit($limit)
            ->get();
        }elseif($type=='completed'){
            $orders=Order::orderDesc()->where('status',2)->where('user_id',$user_id)->where('is_any_product_return',0)
            ->buyOnce()
            ->offset($offset)
            ->limit($limit)
            ->get();
        }elseif($type=='returned'){
            $orders=Order::orderDesc()
            ->buyOnce()
            ->where(function($query) {
                // $query->whereIn('status',[2,3,4])
                $query
                ->Where('is_any_product_return',1)
                // ->orWhere('is_any_product_cancel',1)
                ;

            })
            ->where('user_id',$user_id)
            ->offset($offset)
            ->limit($limit)
            ->get();
        }elseif($type='cancel'){
            $orders=Order::orderDesc()
            ->buyOnce()
            ->where(function($query) {
                $query->whereIn('status',[3,4])
                ->orWhere('is_any_product_cancel',1)
                // ->orWhere('is_any_product_cancel',1)
                ;

            })
            ->where('user_id',$user_id)
            ->offset($offset)
            ->limit($limit)
            ->get();
        }
        else{
            $orders=Order::orderDesc()->where('user_id',$user_id)
            ->buyOnce()
            ->offset($offset)
            ->limit($limit)
            ->get();
        }
        $new=[];
        $add_amount=0;
        $new_amt=0;
        foreach($orders as $order){
            $order_hist=OrderHistory::where('order_id',$order->id)->get();
            $product_order=[];
            if($order_hist){

                foreach($order_hist as $o){
                    $add_amount=0;

                    if($type=='returned'){
                        $check=Returns::where('order_history_id',$o->id)->first();
                        if(!$check){
                            continue;
                        }
                        $add_amount+=$o->price;

                    }elseif($type=='cancel'){

                        if($o->status == 3 || $o->status == 4){
                            $add_amount+=$o->price;
                        }else{
                            continue;
                        }

                    }else{
                        if($o->status == 3 || $o->status == 4){
                            continue;
                        }
                        $new_amt+=$o->price;
                    }

                    $o->order_type=  $o->order_type==1?'buyOnce':'subscribe';
                    $product=Product::where('id',$o->product_id)->first();
                    $o->name=$product->name;
                    $o->image_url=$product->image_url;
                    $o->is_selected='';
                    $product_order[]=$o;
                }
                $status=$order->status;
                if($status == 0){
                    $status_txt= 'Pending';
                }elseif($status == 1){
                    $status_txt= 'Confirmed';
                }elseif($status == 2){
                    // $order_hist_subs_count=OrderHistory::where('order_type',2)->where('order_id',$this->id)->count();
        
                    // if($order_hist_subs_count == 0){
                    //     $status_txt= 'Delivered';
                    // }
                    // $check2=Delivery::where('order_id',$this->id)->whereDate('created_at',date('Y-m-d'))->first();
                    // if($check2){
                    //     $status_txt= 'Confirmed';
                    // }
                    $status_txt= 'Delivered';
                }elseif($status==3||$status==4){
                    $status_txt= 'Cancelled';
                }else{
                    $status_txt= '';
                }

                if($type=='returned'){
                    if($order->status != 3 || $order->status != 4){
                        $order->payable_amount = $add_amount;
                        $order->subtotal = $add_amount;
                        $order->delivery_charges = 0;
                        // $order->status_text='Returned';
                        $order->discount = 0;
                        $status_txt='Returned';
                    }


                }elseif($type=='cancel'){

                    if($order->status != 3 || $order->status != 4){
                        // $order->status_text='Cancelled';
                        $order->payable_amount = $add_amount;
                        $order->subtotal = $add_amount;
                       // unset($order->additionals);

                        $order->delivery_charges = 0;
                        $order->discount = 0;
                        $status_txt='Cancelled';

                    }
                }else{
                    $order->payable_amount = $order->paid_amount;
                    $order->subtotal = $new_amt;

                }
                $order->status_txt=$status_txt;
            }
            $order->product_order=$product_order;
            // $new[]=[];

            $new[]=$order;

        }
        return yt_api_response([
            'status'=>true,
            'message'=>'fetch.',
            'data'=>$new
        ]);
    }


    public function order_details(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'id'=>'required',
            // 'limit'=>'required',
            // 'offset'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $id=$input['id'];
        $order=Order::where('id',$id)->first();
        $order_hist=OrderHistory::where('order_id',$order->id)->get();
        $product_order=[];
        if($order_hist){

            foreach($order_hist as $o){

                $o->order_type=  $o->order_type==1?'buyOnce':'subscribe';
                $product=Product::where('id',$o->product_id)->first();
                $o->name=$product->name;
                $o->image_url=$product->image_url;
                $o->is_selected='';
                $product_order[]=$o;
            }
        }
        $order->product_order=$product_order;
        // $new[]=[];

        $new=$order;

        return yt_api_response([
            'status'=>true,
            'message'=>'fetch.',
            'data'=>$new
        ]);
    }

    public function return_order(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'product_id'=>'required',
            'order_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $order_id=$input['order_id'];
        $user_id=rz_user_id($input);
        $product_id=$input['product_id'];
        $arr=explode(',',$product_id);
        $new_arr=[];
        foreach($arr as $a){
            $new_arr[]=trim($a);
        }
        if(empty($arr)){
            return yt_api_response([
                'status'=>false,
                'message'=>'something went wrong.',
            ]);
        }

        $order_history=OrderHistory::where('order_id',$order_id)->whereIn('id',$new_arr)->get();
        if(count($order_history)){
            $order=Order::where('id',$order_id)->update(['is_any_product_return'=>1]);
            foreach($order_history as $o){
                $check=Returns::where('order_history_id',$o->id)->first();
                if(!$check){
                    $product=Product::where('id',$o->product_id)->first();
                    $arr2=[
                        'user_id'=>$user_id,
                        'order_id'=>$order_id,
                        'product_id'=>$o->product_id,
                        'order_history_id'=>$o->id,
                        'product_name'=>$product->name,
                        'qty'=>$o->qty,
                        'unit'=>$product->unit,
                        'amount'=>$o->price,
                        'issue'=>isset($input['issue'])?$input['issue']:''
                    ];
                    $create= Returns::create($arr2);
                }
            }
        }
        return yt_api_response([
            'status'=>true,
            'message'=>'done.',
            'data'=>[]
        ]);
    }


    public function cancel_item(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'product_id'=>'required',
            'order_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $order_id=$input['order_id'];
        $user_id=rz_user_id($input);
        $product_id=$input['product_id'];
        $arr=explode(',',$product_id);
        $new_arr=[];
        foreach($arr as $a){
            $new_arr[]=trim($a);
        }
        if(empty($arr)){
            return yt_api_response([
                'status'=>false,
                'message'=>'something went wrong.',
            ]);
        }

        $total_amount_refund=0;
        $order_history=OrderHistory::where('order_id',$order_id)->whereIn('id',$new_arr)->get();
        if(count($order_history)){
            $order=Order::where('id',$order_id)->whereIn('status',[0,1])->first();
            if(!$order){
                return yt_api_response([
                    'status'=>false,
                    'message'=>'something went wrong.',
                    'data'=>[]
                ]);
            }
            $up=Order::where('id',$order_id)->update(['is_any_product_cancel'=>1]);
            foreach($order_history as $o){
                $check=OrderHistory::where('id',$o->id)->whereIn('status',[3,4])->first();
                if(!$check){
                    //$product=Product::where('id',$o->product_id)->first();
                    $o->status=3;
                    $o->save();
                    $total_amount_refund+=floatval($o->price);
                }
            }

            if(!empty($total_amount_refund) && $order->payment_mode!='cod'){
                $user=User::where('id',$user_id)->first();
                $old_wallet=$user->wallet;
                $refund_amount=$total_amount_refund;
                $updated_wallet = $old_wallet + $refund_amount;
                $user->wallet=$updated_wallet;
                $user->save();

                $txn=[
                    'user_id'=>$user->id,
                    'payment_mode'=>'wallet',
                    'order_id'=>$order->id,
                    'order_txn_id'=>time().$order->id.$user->id,
                    'type'=>'credit',
                    'old_wallet'=>$old_wallet,
                    'txn_amount'=>$refund_amount,
                    'update_wallet'=>$updated_wallet,
                    'status'=>1,
                    'txn_for'=>'refund',
                    'txn_mode'=>'other',
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $transaction = Transaction::create($txn);
                $this->wallet_txn_notification($user,$refund_amount,'credit');

                $payable_amount=floatval($order->payable_amount);
                $rem_amount = $payable_amount - $total_amount_refund;
                if($rem_amount < 0 ){
                    $rem_amount=0;
                }
                // $order->payable_amount=$rem_amount;
                $order->paid_amount=$rem_amount;
                $order->save();

                $check_cancel=OrderHistory::where('order_id',$order_id)->whereIn('status',[3,4])->count();
                $check_total_product=OrderHistory::where('order_id',$order_id)->count();
                if($check_cancel==$check_total_product){
                    $order->status=3;
                    $order->save();
                }

            }
            return yt_api_response([
                'status'=>true,
                'message'=>'done.',
                'data'=>[]
            ]);
        }
        return yt_api_response([
            'status'=>false,
            'message'=>'something went wrong.',
            'data'=>[]
        ]);
    }


    public function get_subscription_deliveries(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'order_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $id=$input['order_id'];
        $new_arr=[];

        $orderhistory=OrderHistory::where('id',$id)->first();
        $order=Order::where('id',$orderhistory->order_id)->first();

        if(($c=$orderhistory)){
            $dates = Deliveries::where('order_id',$orderhistory->order_id)->pluck('created_at')->toArray();
            $delivered_dates=[];
            foreach($dates as $d){
                $delivered_dates[]=date('Y-m-d',strtotime($d));
            }
            $delivery_dates=explode('|',$c->non_skip_dates);

            $dl_dt=[];
            $i=1;
            foreach($delivery_dates as $d){

                $dl_dt[]=[
                    'id'=>$i,
                    'date'=>date('d-M-Y',strtotime($d)),
                    'is_delivered'=>in_array($d,$delivered_dates) ? 1 : 0
                ];
                $i++;
            }

            $c->delivery_dates=$dl_dt;

            $product=Product::where('id',$c->product_id)->first();
            $c->order_type=  $c->order_type==1?'buyOnce':'subscribe';
            $c->product=$product;
            $c->product->qty=$c->qty;
            $c->product->unit=$c->unit;

            $new_arr=$c;

            return yt_api_response(['status'=>true,'data'=>$new_arr,'message'=>'..']);


        }

        // if($order){
        //     $orderhistory=OrderHistory::where('order_id',$order->id)->get();
        //     if(count($orderhistory)){
        //         foreach($orderhistory as $c){
        //             $dates = Deliveries::where('order_id',$id)->pluck('created_at')->toArray();
        //             $delivered_dates=[];
        //             foreach($dates as $d){
        //                 $delivered_dates[]=date('Y-m-d',strtotime($d));
        //             }
        //             $delivery_dates=explode('|',$c->non_skip_dates);

        //             $dl_dt=[];
        //             $i=1;
        //             foreach($delivery_dates as $d){

        //                 $dl_dt[]=[
        //                     'id'=>$i,
        //                     'date'=>date('d-M-Y',strtotime($d)),
        //                     'is_delivered'=>in_array($d,$delivered_dates) ? 1 : 0
        //                 ];
        //                 $i++;
        //             }

        //             $c->delivery_dates=$dl_dt;
        //             $new_arr[]=$c;
        //         }

        //         return yt_api_response(['status'=>true,'data'=>$new_arr,'message'=>'..']);


        //     }
        // }
        return yt_api_response(['status'=>true,'data'=>$new_arr,'message'=>'something went wrong.']);

    }
}

<?php

namespace App\Http\Controllers\API\DELIVERYBOY;

use App\Http\Controllers\Controller;
use App\Models\Cashcollect;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\Support;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Delivery as Deliveries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\CustomNotifications;
use App\Traits\Delivery;
class OrderController extends Controller
{
    //
    use Delivery,CustomNotifications;
    public function my_deliveries(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            // 'limit'=>'required',
            // 'offset'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $user_id=rz_user_id($input);

        $orders=Order::orderDesc()->whereIn('status',[1,2])->where(function($query)  use ($user_id) {
            $query->where('delivery_boy_id',$user_id)
            ->orWhere('deliver_boy_subscription_id',$user_id)
            ;
        })
        ->get();
        $new=[];
        foreach($orders as $order){
            $order_hist_subs_count=OrderHistory::where('order_type',2)->where('order_id',$order->id)->count();
            $user = User::where('id',$order->user_id)->first();

            if($order->status == 2){
                if($order_hist_subs_count == 0){
                    continue;
                }
            }


            $check2=Deliveries::where('order_id',$order->id)->whereDate('created_at',date('Y-m-d'))->first();

            if($check2){
                continue;
            }


            $order_hist=OrderHistory::where('order_id',$order->id)->get();
            $product_order=[];
            if($order_hist){

                foreach($order_hist as $o){
                    $o->order_type=  $o->order_type==1?'buyOnce':'subscribe';

                    if($o->order_type==1){
                        $check=Deliveries::where('order_history_id',$o->id)->first();
                        if($check){
                            continue;
                        }
                    }elseif($o->order_type==2){
                        $order->delivery_date = date('d M Y');
                        if($o->status == 3 || $o->status == 4){
                            continue;
                        }
                        $deliveries=Deliveries::where('order_id',$o->order_id)->count();
                        if($deliveries<$o->deliveries){
                            $check=Deliveries::where('order_history_id',$o->id)->whereDate('created_at',date('Y-m-d'))->first();

                            if($check){
                                continue;
                            }


                            $check_pending_amount = floatval($order->payable_amount) - floatval($order->paid_amount);
                            if(intval($check_pending_amount) && $o->deliveries_done >= 5){
                                $unit_price = round(floatval($o->price)/intval($o->deliveries));
                                if(intval($user->wallet) >= $unit_price){

                                }else{
                                   continue;
                                }
                            }
                        }
                    }
                    $product=Product::where('id',$o->product_id)->first();
                    $o->name=$product->name;
                    $o->image_url=$product->image_url;
                    $o->is_selected='';
                    $product_order[]=$o;
                }
            }
            $order->user_details=User::where('id',$order->user_id)->first();
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


    public function fetch_completed_order(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'limit'=>'required',
            'offset'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $limit=isset($input['limit']) ? intval($input['limit']) : 10;
        $offset=isset($input['offset']) ? intval($input['offset']) : 0;
        $user_id=rz_user_id($input);
        $orders=Order::orderDesc()->where('status',2)->where(function($query)  use ($user_id) {
            $query->where('delivery_boy_id',$user_id)
            ->orWhere('deliver_boy_subscription_id',$user_id)
            ;
        })
        ->offset($offset)
        ->limit($limit)
        ->get();
        $new=[];
        foreach($orders as $order){
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
            $order->user_details=User::where('id',$order->user_id)->first();
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

    public function complete_order(Request $request)
    {

        $input=$request->all();
        $validator = Validator::make($input, [
            'order_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $order_id=$input['order_id'];
        $order=Order::where('id',$order_id)->first();
        if($order->status == 1){
            $update = Order::where('id',$order_id)->update(['status'=>2,'is_paid'=>1,'completed_at'=>date('Y-m-d H:i:s')]);
        }
        $user_id=rz_user_id();
        $user = User::where('id',$order->user_id)->first();

        $order_histories=OrderHistory::where('order_id',$order_id)->get();
        if($order_histories){

            foreach($order_histories as $o){

                if($o->order_type==1){
                    $check=Deliveries::where('order_history_id',$o->id)->first();
                    if(!$check){
                        $add['user_id']=$o->user_id;
                        $add['order_id']=$o->order_id;
                        $add['order_history_id']=$o->id;
                        $add['delivery_boy_id']=$user_id;
                        $add['status']=1;
                        $c=Deliveries::create($add);
                        $o->status=2;
                        $o->save();

                    }
                }elseif($o->order_type==2){
                    $deliveries=Deliveries::where('order_id',$o->order_id)->count();
                    if($deliveries<$o->deliveries){
                        $check=Deliveries::where('order_history_id',$o->id)->whereDate('created_at',date('Y-m-d'))->first();

                        if(!$check){
                            $add['user_id']=$o->user_id;
                            $add['order_id']=$o->order_id;
                            $add['order_history_id']=$o->id;
                            $add['delivery_boy_id']=$user_id;
                            $add['status']=1;

                            $check_pending_amount = floatval($order->payable_amount) - floatval($order->paid_amount);
                            if(intval($check_pending_amount) && $o->deliveries_done >= 5){
                                $unit_price = round(floatval($o->price)/intval($o->deliveries));
                                if(intval($user->wallet) >= $unit_price){
                                    $old_wallet=floatval($user->wallet);
                                    $amount =floatval($unit_price);
                                    $updated_wallet = $old_wallet - $amount;
                                    $user->wallet=$updated_wallet;
                                    $user->save();
                                    $txn=[
                                        'user_id'=>$order->user_id,
                                        'txn_name'=>'Deduct for Subscription OrderId - #'.$order->id,
                                        'payment_mode'=>'online',
                                        'txn_for'=>'wallet',
                                        'type'=>'debit',
                                        'old_wallet'=>$old_wallet,
                                        'txn_amount'=>$amount,
                                        'update_wallet'=>$updated_wallet,
                                        'status'=>1,
                                        'txn_mode'=>'other',
                                        'order_id'=>$order->id,
                                        'order_txn_id'=>'SUBS'.time().$order->user_id,
                                        'created_at'=>$date=date('Y-m-d H:i:s'),
                                        'updated_at'=>$date,
                                        'wallet_type'=>1,
                                    ];
                                    $create = Transaction::create($txn);

                                    $order->paid_amount =floatval($order->paid_amount) + $amount;
                                    $order->payable_deliveries_count = intval($o->payable_deliveries_count)+1;
                                    $order->save();
                                }else{
                                    return yt_api_response([
                                        'status'=>false,
                                        'message'=>'Please recharge wallet to proceed delivery'
                                    ]);
                                }
                            }
                            $c=Deliveries::create($add);
                            $o->deliveries_done=intval($o->deliveries_done)+1;
                            $o->save();
                        }
                    }
                }
            }
        }

        if(!$order){
            return yt_api_response([
                'status'=>false,
                'message'=>'Something went wrong'
            ]);
        }

        if($update){
            if($order->payment_mode=='cod'){

                if(!(Cashcollect::where('order_id',$order->id)->first())){
                    if($order->payment_mode=='cod'){
                        $ackncod['user_id']=$order->user_id;
                        $ackncod['order_id']=$order->id;
                        $ackncod['deliveryboy_id']=$user_id;
                        $ackncod['status']=0;
                        $ackncod['amount']=$order->payable_amount;
                        $c=Cashcollect::create($ackncod);
                    }
                    $txn=[
                        'user_id'=>$order->user_id,
                        'payment_mode'=>'cod',
                        'order_id'=>$order->id,
                        'order_txn_id'=>time().$order->user_id,
                        'type'=>'debit',
                        'old_wallet'=>0,
                        'txn_amount'=>$order->payable_amount,
                        'update_wallet'=>0,
                        'status'=>1,
                        'txn_mode'=>'cod',
                        'txn_for'=>'order',
                        'created_at'=>$date=date('Y-m-d H:i:s'),
                        'updated_at'=>$date
                    ];
                    $create = Transaction::create($txn);
                    $order->txn_id=$create->id;
                    $order->save();
                }


            }
            $user=User::where('id',$order->user_id)->first();
            $this->order_complete_notification($user,$order);
        }
        return yt_api_response([
            'status'=>true,
            'message'=>'done.',
            'data'=>$order
        ]);

    }

    public function support(Request $request)
    {

        $input=$request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
            'email'=>'required',
            'message'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $input['user_id']=rz_user_id($input);
        $create=Support::create($input);
        if($create){
            return yt_api_response([
                'status'=>true,
                'message'=>'sent..',
                'data'=>$create
            ]);
        }

        return yt_api_response([
            'status'=>false,
            'message'=>'something went wrong.',
            'data'=>$create
        ]);

    }

    public function toggle_status(Request $request)
    {
        $input=$request->all();
        $user_id=rz_user_id($input);
        $user=User::where('id',$user_id)->first();
        if(!$user){
            return yt_api_response([
                'status'=>false,
                'message'=>'something went wrong.',
                'user'=>$user
            ]);
        }

        if($user->is_online==1){
            $user->is_online=0;
            $user->save();
            return yt_api_response([
                'status'=>true,
                'message'=>'user offline.',
                'is_online'=>$user->is_online,
                'user'=>$user
            ]);
        }elseif($user->is_online==0){
            $user->is_online=1;
            $user->save();
            return yt_api_response([
                'status'=>true,
                'message'=>'user online.',
                'is_online'=>$user->is_online,
                'user'=>$user
            ]);
        }

        return yt_api_response([
            'status'=>false,
            'message'=>'something went wrong.',
            'user'=>$user
        ]);

    }

    public function get_cash_collects(Request $request)
    {
        $input=$request->all();
        $user_id=rz_user_id($input);
        $fetch=Cashcollect::collects()->where('deliveryboy_id',$user_id)->get();
        $new=[];
        $total=0;
        if(count($fetch)){
            foreach($fetch as $f){
                $order=Order::where('id',$f->order_id)->first();
                $f->amount=$amount=$order->payable_amount;
                $total+=$amount;
                $new[]=$f;
            }
        }
        return yt_api_response([
            'status'=>true,
            'message'=>'....',
            'amount'=>$total,
            'data'=>$new
        ]);
    }

    public function handover_cash(Request $request)
    {
        $input=$request->all();
        $user_id=rz_user_id($input);
        $fetch=Cashcollect::collects()->where('deliveryboy_id',$user_id)->update(['status'=>1]);

        // if(count($fetch)){
        //     foreach($fetch as $f){

        //     }
        // }
        return yt_api_response([
            'status'=>true,
            'message'=>'....',
            'amount'=>Cashcollect::collects()->where('deliveryboy_id',$user_id)->sum(),
            'data'=>$fetch
        ]);
    }
}

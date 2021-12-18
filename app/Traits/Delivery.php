<?php
namespace App\Traits;

use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\Returns;

trait Delivery{


    public function today_deliveries_trait($user_id=0)
    {
        $date=date('Y-m-d');
        $time=time();
        $new_subs=[];
        $buyonce=OrderHistory::whereDate('delivery_date', $date)->buyOnce()->orderDesc()->whereIn('status',[0,1])->get();
        $subscriptions=OrderHistory::subscriptions()->whereIn('status',[0,1])->orderDesc()->get();
        foreach($subscriptions as $s){
            if(rz_have_vacation($s->user_id,$date,$s->vacations)){
                continue;
            }
            $check=Returns::where('order_history_id',$s->id)->first();
            if($check){
                continue;
            }
            if($s->deliveries_done > $s->deliveries){
                continue;
            }
            if($time < strtotime($s->start_date)){
                continue;
            }
            if(strtotime($date) > strtotime($s->end_date)){
                continue;
            }
            $skip_days_arr=explode('|',$s->skip_dates);
            if(!empty($skip_days_arr)){
                if(in_array($date,$skip_days_arr)){
                    continue;
                }
            }
            $new_subs[]=$s;
        }

        return [
            'buyonce'=>$buyonce,
            'subscriptions'=>$subscriptions
        ];
    }


    public function tomorrow_deliveries_trait($user_id=0)
    {
        $date=date('Y-m-d', strtotime(' +1 day'));
        $new_subs=[];
        $buyonce=OrderHistory::whereDate('delivery_date', $date)->buyOnce()->orderDesc()->whereIn('status',[0,1])->get();
        $subscriptions=OrderHistory::subscriptions()->whereIn('status',[0,1])->orderDesc()->get();
        foreach($subscriptions as $s){
            if(rz_have_vacation($s->user_id,$date,$s->vacations)){
                continue;
            }
            if($s->deliveries_done > $s->deliveries){
                continue;
            }
            $check=Returns::where('order_history_id',$s->id)->first();
            if($check){
                continue;
            }
            if(strtotime($date) < strtotime($s->start_date)){
                continue;
            }
            if(strtotime($date) > strtotime($s->end_date)){
                continue;
            }
            $skip_days_arr=explode('|',$s->skip_dates);
            if(!empty($skip_days_arr)){
                if(in_array($date,$skip_days_arr)){
                    continue;
                }
            }
            $new_subs[]=$s;
        }

        return [
            'buyonce'=>$buyonce,
            'subscriptions'=>$subscriptions
        ];
    }


    public function deliveries_by_date_trait($user_id=0,$date)
    {
        $time=strtotime($date);
        $new=[];
        $orders=OrderHistory::whereIn('status',[0,1])->where('user_id',$user_id)->orderBy('id','desc')->get();
        // $subscriptions=OrderHistory::subscriptions()->where('end_date','<',$date.' 23:59:59')->whereIn('status',[0,1])->get();
        foreach($orders as $s){
            if($s->order_type==2){
                if(rz_have_vacation($s->user_id,$date,$s->vacations)){
                    continue;
                }
                if($s->deliveries_done > $s->deliveries){
                    continue;
                }
                if($time < strtotime($s->start_date)){
                    continue;
                }
                if($time > strtotime($s->end_date)){
                    continue;
                }
                $check=Returns::where('order_history_id',$s->id)->first();
                if($check){
                    continue;
                }
                $skip_days_arr=explode('|',$s->skip_dates);
                if(!empty($skip_days_arr)){
                    if(in_array($date,$skip_days_arr)){
                        continue;
                    }
                }
            }elseif($s->order_type==1){
                if($time != strtotime(date('Y-m-d',strtotime($s->created_at)))){
                    continue;
                }
            }
            $product=Product::where('id',$s->product_id)->first();
            $s->order_type=  $s->order_type==1?'buyOnce':'subscribe';
            $s->product=$product;
            $s->product->qty=$s->qty;
            $s->product->unit=$s->unit;


            $new[]=$s;
        }

        return $new;
    }

}

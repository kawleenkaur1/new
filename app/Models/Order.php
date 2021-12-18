<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=['user_id', 'subtotal', 'discount', 'coupon_discount',
    'gst', 'delivery_charges', 'payable_amount', 'coupon_id', 'shipping_name','cashback_discount','assign_time','pickup_time','reach_time',
    'shipping_phone', 'shipping_flat', 'shipping_pincode', 'shipping_location','lat','lng','deliver_boy_subscription_id','delivery_date','order_type','warehouse_id',
    'is_any_product_return','is_any_product_cancel','payment_mode', 'delivery_boy_id', 'status', 'is_paid', 'txn_id', 'created_at', 'updated_at','is_refunded','completed_at','paid_amount','payable_deliveries_count'];

    protected $appends = ['additionals','pending_amount'];

    //   public function getPayableAmountAttribute($value)
    // {

    //     if($this->is_any_product_cancel && ($this->status != 3 || $this->status != 4)){
    //         $payable_amount = floatval($this->paid_amount);
    //     }else{
    //         $payable_amount=$value;
    //     }
    //    // $payable_amount=$value;
    //     return $payable_amount;
    // }

    // public function getSubtotalAttribute($value)
    // {

    //     if($this->is_any_product_cancel && ($this->status != 3 || $this->status != 4)){
    //        $oh=OrderHistory::where('order_id',$this->id)->whereIn('status',[3,4])->get();
    //        $item_amount=0;
    //        foreach($oh as $o){
    //            $item_amount+=$o->price;
    //        }
    //         $payable_amount=floatval($value) - $item_amount;
    //     }else{
    //         $payable_amount=$value;
    //     }

    //     return $payable_amount;
    // }


    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id','desc');
    }

    public function scopeNotCancel($query)
    {
        return $query->whereNotIn('status',[3,4]);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status',[0,1]);
    }

    public function scopeFloting($query)
    {
        return $query->whereIn('status',[2])->where('payment_mode',"cod");

    }

    public function scopeCompleted($query)
    {
        return $query->where('status',2);
    }

    public function scopeCancelled($query)
    {
        return $query->whereIn('status',[3,4]);
    }

    public function scopeCouponHistory($query)
    {
        return $query->where('coupon_id','!=',0);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M y g:i a',strtotime($value));
    }

    public function getDeliveryDateAttribute($value)
    {
        return date('d M y',strtotime($value));
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo('App\Models\User','delivery_boy_id');
    }

    public function subsdeliveryBoy()
    {
        return $this->belongsTo('App\Models\User','deliver_boy_subscription_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\OrderHistory');
    }

    public function getPendingAmountAttribute()
    {
        $amount=$this->payable_amount;
        return ($amount-$this->paid_amount);

    }

    public function scopeSubscribe($query)
    {
        return $query->where('order_type',2);
    }

    public function scopeBuyOnce($query)
    {
        return $query->where('order_type',1);
    }

    public function getAdditionalsAttribute()
    {
        $status=$this->status;
        $cancel_power=0;
        $return_power=0;

        if($status<2 ){
            $cancel_power=1;
        }
        $updated_at=$this->completed_at;
        $new_timestamp = strtotime($updated_at) + 60*60;
        if(time()<$new_timestamp && $status==2 && $this->is_any_product_return == 0){
            $return_power=1;
        }
        // $time = date('H:i', $timestamp);
        // if($this->is_any_product_cancel && ($this->status != 3 || $this->status != 4)){
        // }
        return [
            'cancel_power'=>$this->is_any_product_cancel ? 0 : $cancel_power,
            'return_power'=>$return_power
        ];
    }


    // public function getStatusTxtAttribute()
    // {
    //     $status=$this->status;
    //     if($status == 0){
    //         return 'Pending';
    //     }elseif($status == 1){
    //         return 'Confirmed';
    //     }elseif($status == 2){
    //         // $order_hist_subs_count=OrderHistory::where('order_type',2)->where('order_id',$this->id)->count();

    //         // if($order_hist_subs_count == 0){
    //         //     return 'Delivered';
    //         // }
    //         // $check2=Delivery::where('order_id',$this->id)->whereDate('created_at',date('Y-m-d'))->first();
    //         // if($check2){
    //         //     return 'Confirmed';
    //         // }
    //         return 'Delivered';
    //     }elseif($status==3||$status==4){
    //         return 'Cancelled';
    //     }else{
    //         return '';
    //     }
    // }
}

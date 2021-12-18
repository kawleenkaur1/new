<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable=['order_id', 'user_id', 'product_id', 'order_type', 'qty', 'price', 'deliveries', 'start_date', 'shipping_name', 'shipping_phone', 'shipping_flat', 'shipping_pincode', 'shipping_location', 'status', 'created_at', 'updated_at',
'end_date','deliveries_done','skip_dates','non_skip_dates','delivery_date','actual_qty','unit'
];

    protected $appends = ['additionals'];

    public function getStartDateAttribute($value)
    {
        return date('d M y ',strtotime($value));
    }
    public function getEndDateAttribute($value)
    {
        return date('d M y ',strtotime($value));
    }
    public function getDeliveryDateAttribute($value)
    {
        return date('d M y ',strtotime($value));
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id','desc');
    }


    public function scopeSubscriptions($query)
    {
        return $query->where('order_type',2);
    }

    public function scopeBuyOnce($query)
    {
        return $query->where('order_type',1);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status',[0,1]);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M y g:i a',strtotime($value));
    }

    public function getAdditionalsAttribute()
    {
        // $skip_days=$this->skip_days;
        // $deliveries=$this->deliveries;
        // if($skip_days != 0){
        //     $additional_days=round($deliveries/$skip_days);
        // }else{
        //     $additional_days=0;
        // }
        // $total_days=$additional_days+$deliveries;
        // $end_date=date('d M Y', strtotime($this->start_date. ' + '.intval($total_days).' days'));
        return [
            'end_date'=>$this->end_date
        ];
    }

}

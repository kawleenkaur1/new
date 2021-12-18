<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $fillable=['order_id', 'order_history_id', 'user_id', 'product_id', 'product_name', 'qty', 'unit', 'amount', 'product_image', 'issue', 'status', 'is_refunded', 'created_at', 'updated_at'];

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id','desc');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function orderhistory()
    {
        return $this->belongsTo('App\Models\OrderHistory','order_history_id');
    }
}

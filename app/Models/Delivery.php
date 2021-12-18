<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable=['delivery_boy_id','warehouse_id','user_id','order_id','order_history_id','status','created_at','updated_at'];

      public function order()
    {
        return $this->belongsTo('App\Models\Order','delivery_boy_id');
    }
}

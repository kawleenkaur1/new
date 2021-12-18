<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponApply extends Model
{
    use HasFactory;
    protected $fillable=['order_id', 'user_id', 'coupon_id', 'status', 'created_at', 'updated_at'];
}

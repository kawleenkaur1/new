<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashcollect extends Model
{
    use HasFactory;

    protected $fillable=['deliveryboy_id','user_id','order_id','amount','status','created_at','updated_at'];


    public function getCreatedAtAttribute($value)
    {
        return date('d M y g:i a',strtotime($value));
    }

    public function scopeCollects($query)
    {
        return $query->where('status',0);
    }


}

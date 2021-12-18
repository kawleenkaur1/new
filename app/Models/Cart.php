<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'order_type', 'deliveries', 'start_date', 'address_id', 'qty', 'created_at', 'updated_at','frequency_id',
    'skip_days'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function scopeBuyOnce($query)
    {
        return $query->where('order_type',1);
    }

    public function scopeSubscribe($query)
    {
        return $query->where('order_type',2);
    }
}

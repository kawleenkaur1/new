<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable=['user_id', 'product_id', 'stock', 'stock_status', 'status', 'added_by', 'comment', 'price', 'created_at', 'updated_at','order_id'];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
    public function scopeInStock($query)
    {
        return $query->where('stock_status',1);
    }

    public function scopeOutStock($query)
    {
        return $query->where('stock_status',2);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'use_limit', 'position', 'link_source', 'link_id', 'type', 'discount', 'max_discount', 'status', 'created_at', 'updated_at','added_by',
            'starts_on','expires_on','min_order_amount'
        ];

        public function getStartsOnAttribute($value)
        {
            return date('d M y',strtotime($value));
        }

        public function getExpiresOnAttribute($value)
        {
            return date('d M y',strtotime($value));
        }
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeOrderAsc($query)
    {
        return $query->orderBy('position','asc');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->added_by = Auth::user()->id;
        });
    }
}

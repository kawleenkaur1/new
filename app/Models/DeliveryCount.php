<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCount extends Model
{
    use HasFactory;
    protected $appends=['is_selected'];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function getIsSelectedAttribute()
    {
        return '';
    }
}

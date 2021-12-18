<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'days', 'description', 'skip_days', 'position', 'status', 'created_at', 'updated_at'];

    protected $appends=['is_selected'];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeOrderAsc($query)
    {
        return $query->orderBy('position','asc');
    }

    public function getIsSelectedAttribute()
    {
        return '';
    }
}

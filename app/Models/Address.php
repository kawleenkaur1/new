<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['user_id',
    'main_location',
    'main_society',
    'name', 'phone', 'flat', 'city', 'state', 'country', 'pincode', 'location', 'status','lat','lng', 'is_default', 'created_at', 'updated_at'];

    protected $appends=['is_selected'];

    public function getIsSelectedAttribute()
    {
        return '';
    }

    public function getLocationAttribute($value)
    {
        $location=$this->main_society;
        return trim($location);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default',1);
    }
}

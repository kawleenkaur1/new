<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable=['name', 'location', 'pincode', 'lat', 'lon', 'status', 'position', 'created_at', 'updated_at'];


    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}

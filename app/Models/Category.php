<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'position', 'any_discount', 'discount', 'show_homepage_top', 'show_homepage_bottom', 'status', 'created_at', 'updated_at','added_by'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('/').'/public/uploads/categories/'.$this->image;
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'image', 'position', 'any_discount', 'discount', 'status', 'created_at', 'updated_at','added_by'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('/').'/public/uploads/subcategories/'.$this->image;
    }


    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeOrderAsc($query)
    {
        return $query->orderBy('position','asc');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->added_by = Auth::user()->id;
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['link_id', 'link_type', 'name', 'image', 'type', 'status','banner_type', 'created_at','position' ,'updated_at','link_parent_id'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('/').'/public/uploads/banners/'.$this->image;
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeOrderAsc($query)
    {
        return $query->orderBy('position','asc');
    }

    public function scopeTopBanner($query)
    {
        return $query->where('type',1);
    }

    public function scopeBottomBanner($query)
    {
        return $query->where('type',2);
    }
    public function scopecategoryBanner($query)
    {
        return $query->where('type',3);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['app_name','logo','favicon','support_email','support_phone','terms','privacy_policy','about_us','invite_friends','referral_rewards','loyality_rewards','delivery_charges','splashing_text','cashback_signup','about_us_video'];
    protected $appends = ['logo_url','favicon_url','about_us_video'];
    public $timestamps = false;

    public function getLogoUrlAttribute()
    {
        return URL::to('/').'/public/uploads/settings/'.$this->logo;
    }

    public function getFaviconUrlAttribute()
    {
        return URL::to('/').'/public/uploads/settings/'.$this->favicon;
    }

    public function getAboutUsVideolAttribute()
    {
        return URL::to('/').'/public/uploads/settings/'.$this->about_us_video;
    }

    
}

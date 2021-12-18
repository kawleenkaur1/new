<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type','image','city','state',
        'phone','pincode','location','latitude','longitude','cityadmin_id','added_by','referral_from','is_online','wallet','cashback_wallet','warehouse_id',
        'email_verified','phone_verified','email_verified_at','phone_verified_at','referral_code','device_id','device_type','device_token','model_name','status','remember_token',
        'cod','aadhar_number','aadhar_image','pan_number','pan_image','dl_number','dl_image','pincode_allowance'
    ];

    protected $appends = ['image_url','aadhar_image_url','pan_image_url','dl_image_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the user's Image URL.
     *
     * @param  string  $value
     * @return string
     */
    public function getImageUrlAttribute()
    {
        // if($this->user_type == 4){
        //     return URL::to('/').'/public/uploads/user/cityadmin/'.$this->image;
        // }elseif($this->user_type == 3){
        //     return URL::to('/').'/public/uploads/user/deliveryboy/'.$this->image;
        // }elseif($this->user_type == 5){
        //     return URL::to('/').'/public/uploads/user/warehouse/'.$this->image;
        // }else{
        //     return URL::to('/').'/public/uploads/user/'.$this->image;
        // }
        return URL::to('/').'/public/uploads/user/'.$this->image;
    }

    public function getAadharImageUrlAttribute()
    {
        return URL::to('/').'/public/uploads/user/aadhar/'.$this->aadhar_image;
    }

    public function getPanImageUrlAttribute()
    {
        return URL::to('/').'/public/uploads/user/pan/'.$this->pan_number;
    }

    public function getDlImageUrlAttribute()
    {
        return URL::to('/').'/public/uploads/user/dl/'.$this->dl_image;
    }

    /**
     * Set the user's email.
     *
     * @param  string  $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    // public function setAddedByAttribute($value)
    // {
    //     $this->attributes['added_by'] = Auth::user()->id;
    // }

    // public static function boot()
    // {
    //     parent::boot();
    //     static::saving(function ($model) {
    //         $model->added_by = Auth::user()->id;
    //     });
    // }

    /**
     * Set the user's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }


    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeNewUser($query)
    {
        return $query->orderBy('id','desc');
    }

    public function scopeCityAdmin($query)
    {
        return $query->where('user_type',4);
    }

    public function scopeWarehouse($query)
    {
        return $query->where('user_type',5);
    }

    public function scopeCustomerUser($query)
    {
        return $query->where('user_type',2);
    }

    public function scopeDeliveryBoy($query)
    {
        return $query->where('user_type',3);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveryboyorders()
    {
        return $this->hasMany(Order::class,'delivery_boy_id');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}

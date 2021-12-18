<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpUser extends Model
{
    use HasFactory;

    protected $fillable= ['name', 'email', 'phone', 'email_verified', 'phone_verified', 'otp', 'user_type', 'created_at', 'updated_at','referral_from'];

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id','desc');
    }

}

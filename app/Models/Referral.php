<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable=['refer_from', 'refer_to', 'status', 'created_at', 'updated_at','earn_points'];

    public function referralfrom()
    {
        return $this->belongsTo('App\Models\User','refer_from');
    }

    public function referralto()
    {
        return $this->belongsTo('App\Models\User','refer_to');
    }
}

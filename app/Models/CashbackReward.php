<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CashbackReward extends Model
{
    use HasFactory;
    protected $table = 'cashback_rewards';

    protected $fillable = ['amount', 'cashback_perct'];

}

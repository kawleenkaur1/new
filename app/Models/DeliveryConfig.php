<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryConfig extends Model
{
    use HasFactory;

    protected $table = "delivery_configs";

    protected $fillable = ['id', 'type', 'time_take', 'time_slots', 'status', 'created_at', 'updated_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashDeposit extends Model
{
    use HasFactory;

    protected $fillable = ['delivery_boy_id','warehouse_id','amount','mode','txn_id','document','description','created_at','updated_at'];
}

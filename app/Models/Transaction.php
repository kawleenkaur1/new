<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable=['user_id', 'order_id', 'txn_name','order_txn_id', 'payment_mode', 'type', 'old_wallet', 'txn_amount', 'update_wallet', 'status', 'txn_mode', 'bank_name', 'bank_txn_id', 'ifsc', 'account', 'created_at', 'updated_at',
'txn_for','wallet_type'
];

    public function getTxnForAttribute($value)
    {
        if(!empty($this->txn_name)){
            return $this->txn_name;
        }else{
            return $value;

        }
    }

    public function scopeWalletTxn($query)
    {
        return $query->where('payment_mode','wallet')->orWhere('txn_for','wallet');
    }

    public function scopeOnlineTxn($query)
    {
        return $query->where('payment_mode','online');
    }

    public function scopeMainWallet($query)
    {
        return $query->where('wallet_type',1);
    }

    public function scopeCashbackWallet($query)
    {
        return $query->where('wallet_type',2);
    }

    // public function setOrdrTxnIdAttribute($value)
    // {
    //     $this->attributes['order_txn_id'] = empty($value) ? time() : $value ;
    // }

    public function scopeNewest($query)
    {
        return $query->orderBy('id','desc');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

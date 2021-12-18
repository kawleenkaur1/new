<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;
    protected $table = 'careers';

    protected $fillable = [ 'user_id', 'name', 'email', 'message','subject', 'status','type','phone','city','state', 'created_at', 'updated_at'];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopePending($query)
    {
        return $query->where('status',0);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status',1);
    }

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id','desc');
    }
}

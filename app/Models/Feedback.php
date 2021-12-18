<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'subject', 'experience', 'message', 'status', 'created_at', 'updated_at'];

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id','desc');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $appends=['added_on'];

    function getAddedOnAttribute()
    {
        return date('d M Y g:i A',strtotime($this->created_at));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M y g:i a',strtotime($value));
    }
}

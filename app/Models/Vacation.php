<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;

    protected $fillable=['user_id', 'start_date', 'end_date', 'other_data', 'created_at', 'updated_at'];

    public function getStartDateAttribute($value)
    {
        return date('d M y ',strtotime($value));
    }
    public function getEndDateAttribute($value)
    {
        return date('d M y ',strtotime($value));
    }
}

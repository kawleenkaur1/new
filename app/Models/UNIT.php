<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UNIT extends Model
{
    use HasFactory;
    protected $table = 'unit';

    protected $fillable = [ 'name', 'status', 'added_by', 'created_at', 'updated_at'];


    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeOrderAsc($query)
    {
        return $query->orderBy('position','asc');
    }

}

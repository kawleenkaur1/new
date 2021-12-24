<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $table="blogs";
    protected $fillable=['title','image','description','catagory_id','status'];
    public function category()
    {
      return $this->belongsTo('App\Models\Category','category_id');
    }
}

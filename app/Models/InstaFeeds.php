<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstaFeeds extends Model
{
    use HasFactory;
    protected $table="insta_feed";
    protected $fillable=['title','description','image','hyperlink','status','position'];
}

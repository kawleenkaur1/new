<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductConnection extends Model
{
    use HasFactory;
    protected $fillable=['name', 'product_id', 'category_id', 'created_at', 'updated_at'];
}

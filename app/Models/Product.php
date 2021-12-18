<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'subcategory_id', 'name', 'image', 'mrp', 'discount', 'discount_type','attributes',
        'selling_price', 'subscription_price', 'show_in_subscriptions', 'stock', 'qty', 'unit',
        'position', 'status', 'mark_as_new', 'created_at', 'updated_at','added_by','description','mark_as_bestoffers',
        'no_of_pieces','serves','cooking_time','net_wt','gross_wt', 'tags','hifen_name','category_ids_string','is_combo','packing_type','mark_as_hotselling','hover_image','gallery',
        'is_deal','start_date','end_date'
    ];

    protected $appends = ['image_url','cart_data','is_wishlist','hover_url','img_path'];

    public function getImageUrlAttribute()
    {
        return URL::to('/').'/public/uploads/products/'.$this->image;
    }
      public function getHoverUrlAttribute()
    {
        return URL::to('/').'/public/uploads/products/'.$this->hover_image;
    }

      public function getImgPathAttribute()
    {
        return URL::to('/').'/public/uploads/products/';
    }



    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory','subcategory_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeDeal($query)
    {
        return $query->where('is_deal',1);
    }

    public function scopeOrderAsc($query)
    {
        return $query->orderBy('position','asc');
    }

    public function scopeWhatsnew($query)
    {
        return $query->where('mark_as_new',1);
    }

    public function scopeBestOffers($query)
    {
        return $query->where('mark_as_bestoffers',1);
    }

    public function getCartDataAttribute()
    {
        if(!Auth::check())
        {

            $user_id = 0;
            return [
                'is_cart'=>0,
                'qty'=>0,
                'cart_id'=>0
            ];
        } else{
            $user_id = Auth::user()->id;
            $c= Cart::where('user_id',$user_id)->where('product_id',$this->id)->first();
            if($c){
                return [
                    'is_cart'=>1,
                    'qty'=>$c->qty,
                    'cart_id'=>$c->id
                ];
            }else{
                return [
                    'is_cart'=>0,
                    'qty'=>0,
                    'cart_id'=>0
                ];
            }
        }

    }

    public function getisWishlistAttribute()
    {
        if(!Auth::check())
        {

          //  $user_id = 0;
            return 0;
        } else{
            $user_id = Auth::user()->id;
            $c= Wishlist::where('user_id',$user_id)->where('product_id',$this->id)->first();
            if($c){
                return 1;
            }else{
               return 0;
            }
        }

    }


    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->added_by = Auth::user()->id;
        });
    }
}

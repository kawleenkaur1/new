<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //

    public function get_products_by_subcategory(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'subcategory_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $fetch = Product::where('subcategory_id',$input['subcategory_id'])->active()->orderBy('name','asc')->get();
        // $fetch = Product::active()->orderBy('name','asc')->get();

        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($fetch) ? 'fetch successfully' : 'Sorry! No data found',
            'data'=>$fetch
        ]);

    }

    public function get_product_details(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $product = Product::where('id',$input['product_id'])->first();
        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($product) ? 'fetch successfully' : 'Sorry! No data found',
            'product'=>$product
        ]);
    }

    public function toggle_wishlist(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $check=Wishlist::where('product_id',$input['product_id'])->first();
        if($check){
            $remove=Wishlist::where('id',$check->id)->delete();
            if($remove){
                return yt_api_response([
                    'status'=>true,
                    'is_add'=>false,
                    'messsage'=>'removed',
                    'data'=>$remove
                ]);
            }
            return yt_api_response([
                'status'=>false,
                'is_add'=>false,
                'messsage'=>'something went wrong',
                'data'=>$remove
            ]);
        }
        $rq=[
            'user_id'=> rz_user_id($input),
            'product_id'=>$input['product_id'],
            'created_at'=>$date=date('Y-m-d H:i:s'),
            'updated_at'=>$date
        ];
        $create=Wishlist::create($rq);
        if($create){
            return yt_api_response([
                'status'=>true,
                'is_add'=>true,
                'messsage'=>'added',
                'data'=>$create
            ]);
        }
        return yt_api_response([
            'status'=>false,
            'is_add'=>false,
            'messsage'=>'something went wrong',
            'data'=>$create
        ]);
    }

    public function fetch_wishlists(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id();
        // $products=Product::join('wishlists', 'products.id', '=', 'wishlists.product_id')->where('wishlists.user_id',$user_id)->get();
        $new=[];
        $wishlists=Wishlist::where('user_id',$user_id)->get();
        if(count($wishlists)){
            foreach($wishlists as $w){
                $product=Product::where('id',$w->product_id)->active()->first();
                if($product){
                    $new[]=$product;
                }
            }
        }
        return yt_api_response([
            'status'=>true,
            'messsage'=>'fetch',
            'data'=>$new
        ]);
    }

    public function global_search(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $search = $input['search'];

        if(strlen($search)>1){
            $products=Product::active()->where('name', 'like', '%'.$search.'%')->get();
        }else{
            $products=[];
        }


        // $subcategory=Subcategory::where('name', 'like', '%'.$search.'%')->get();
        // $category=Category::where('name', 'like', '%'.$search.'%')->get();



        return yt_api_response([
            'status'=>true,
            'messsage'=>'fetch',
            'products'=>$products
        ]);
    }
}

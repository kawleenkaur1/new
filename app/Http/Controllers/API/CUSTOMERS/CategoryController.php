<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //

    public function get_subcategories(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $fetch = Subcategory::where('category_id',$input['category_id'])->active()->orderAsc()->get();
        $top_banners = Banner::active()->categoryBanner()->orderAsc()->get();
        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($fetch) ? 'fetch successfully' : 'Sorry! No data found',
            'banners'=>$top_banners,
            'data'=>$fetch
        ]);
    }


    public function get_all_subcategories(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $fetch = Subcategory::active()->orderAsc()->get();
        $top_banners = Banner::active()->topBanner()->orderAsc()->get();
        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($fetch) ? 'fetch successfully' : 'Sorry! No data found',
            'banners'=>$top_banners,
            'data'=>$fetch
        ]);
    }
}

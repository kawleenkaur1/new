<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CashDeposit;
use App\Models\Frequency;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\ProductPrice;
use App\Models\Society;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CommonController extends Controller
{

    public function get_frequencies(Request $request)
    {
        $fetch = Frequency::active()->orderAsc()->get();

        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($fetch) ? 'fetch successfully' : 'Sorry! No data found',
            'data'=>$fetch
        ]);
    }

    public function check_if_location_in_service(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lat'=>'required',
            'lon'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $lat=$input['lat'];
        $lon=$input['lon'];
        $location=rz_get_location_data_by_lat_long($lat,$lon);
        $results=$location['results'][0]['address_components'];
        $count_result=count($results);
        $pincode=$results[$count_result-1]['long_name'];

        $city=$results[$count_result-3]['long_name'];

        // dd($pincode);
        $check=Location::active()->where(function($query)  use ($pincode,$city) {
            $query->where('pincode',$pincode)
            ->orWhere('name','LIKE', '' . $city . '%');
        })->first();
        $check_2=Society::active()->where('pincode',$pincode)->first();
        if($check){
            return yt_api_response([
                'status'=>true,
                'message'=>'Valid Location',
                'formatted_address'=>$location['results'][0]['formatted_address'],
               'location'=> $results,
                'data'=>$check
            ]);
        }
        return yt_api_response([
            'status'=>false,
            'message'=>'You seems to be outside serviceable area please choose other pincodes from the list or try changing the address',
            'data'=>$check
        ]);
    }


    public function submit_cash(Request $request)
    {
        $input=$request->all();
        // $validator = Validator::make($input, [
        //     'user_id'=>'required',
        //     'amount'=>'required'
        // ]);
        // if ($validator->fails()) {
        //     $message = $this->yt_validator_error_messages($validator);
        //     return response()->json(['status' => false,'message'=>$message]);
        // }
        $user_id=$input['user_id'];
        $user = User::where('id',$user_id)->first();
        $arr['delivery_boy_id']=$user_id;
        $arr['warehouse_id']=$user->warehouse_id;
        $arr['amount'] = floatval($input['amount']);
        $arr['mode'] = $input['mode'];
        $arr['txn_id'] = $input['txn_id'];
        if($input['flag']==1){
            if($_FILES['document']['size']>0){
                $documentName = time().'.'.$request->document->extension();
                $request->document->move(public_path('uploads/cash_deposits/'), $documentName);
                $arr['document'] = $documentName;
            }
        }
        $update=CashDeposit::create($arr);
        return response()->json([
            'status'=>true,
            'message'=>'created',
            'data'=>$update
        ]);
    }

    public function remove_stock_from_location(Request $request)
    {
        $input=$request->all();
        $location_id = $input['location_id'];
        $product_id = $input['product_id'];
        $user_id = $input['user_id'];
        $stock = $input['stock'];
        $order_id = $input['order_id'];
        $productprice = ProductPrice::where('product_id',$product_id)->where('location_id',$location_id)->first();
        if($productprice){
            $st = intval($productprice->stock) - intval($stock);
            $productprice->stock = $st<0?0:$st;
            $productprice->save();

            $a['product_id']=$input['product_id'];
            $a['user_id']=0;
            $a['stock']=$input['stock'];
            $a['stock_status']=2;
            $a['added_by']=$user_id;
            // $a['comment']=$input['comment'];
            $a['order_id'] = $order_id;
            $create = Inventory::create($a);
        }
        return response()->json([
            'status'=>true,
            'message'=>'created',
        ]);
    }


    public function add_stock_from_location(Request $request)
    {
        $input=$request->all();
        $order_id = $input['order_id'];
        $user_id = $input['user_id'];
        $stocksdelete = Inventory::where('added_by',$user_id)->where('order_id',$order_id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>'created',
        ]);
      
    }
}

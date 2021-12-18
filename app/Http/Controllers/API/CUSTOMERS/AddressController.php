<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Location;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    //

    public function get_addresses(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $user_id = rz_user_id($input);
        $fetch = Address::where('user_id',$user_id)->get();
        return yt_api_response([
            'status'=>true,
            'messsage'=>!empty($fetch) ? 'fetch successfully' : 'Sorry! No data found',
            'data'=>$fetch
        ]);
    }


    public function add_address(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
            'phone'=>'required',
            'flat'=>'required',
            //'pincode'=>'required',
            'location'=>'required',
            // 'lat'=>'required',
            // 'lng'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $input['user_id'] =$user_id= rz_user_id($input);
        $input['pincode']=$input['pincode'];
        $input['is_default']=1;
        $create = Address::create($input);
        $update=Address::where('id','!=',$create->id)->where('user_id',$user_id)->update(['is_default'=>0]);
        return yt_api_response([
            'status'=>true,
            'message'=>'Added :)',
            'data'=>$create
        ]);


    }

    // public function add_address(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'name'=>'required',
    //         'phone'=>'required',
    //         'flat'=>'required',
    //         //'pincode'=>'required',
    //         'location'=>'required',
    //         'lat'=>'required',
    //         'lng'=>'required'
    //     ]);
    //     if ($validator->fails()) {
    //         $message = yt_validator_error_messages($validator);
    //         return yt_api_response(['status' => false,'message'=>$message]);
    //     }
    //     $input['user_id'] =$user_id= rz_user_id($input);

    //     $lat=$input['lat'];
    //     $lng=$input['lng'];
        // $location=rz_get_location_data_by_lat_long($lat,$lng);
        // $results=$location['results'][0]['address_components'];
        // $count_result=count($results);
        // $pincode=$results[$count_result-1]['long_name'];
    //     $check=Location::active()->where('pincode',$pincode)->first();
    //     if($check){

    //         $input['pincode']=$pincode;
    //         $input['is_default']=1;

    //     $create = Address::create($input);
    //     $update=Address::where('id','!=',$create->id)->where('user_id',$user_id)->update(['is_default'=>0]);
    //     return yt_api_response([
    //         'status'=>true,
    //         'message'=>'Added :)',
    //         'data'=>$create
    //     ]);
    //     }else{
    //         return yt_api_response([
    //             'status'=>false,
    //             'message'=>'You seems to be outside serviceable area please choose other pincodes from the list or try changing the address',
    //             'data'=>$check
    //         ]);
    //     }


    // }

    public function select_default_address(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $id=$input['id'];
        $user_id=rz_user_id($input);

        $update=Address::where('id',$id)->where('user_id',$user_id)->update(['is_default'=>1]);
        Address::where('id','!=',$id)->where('user_id',$user_id)->update(['is_default'=>0]);
        return yt_api_response([
            'status'=>true,
            'message'=>'Updated :)',
            'data'=>$update
        ]);
    }

    public function delete_address(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'=>'required',
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $id = $input['id'];
        $user_id = rz_user_id($input);
        $delete = Address::where('user_id',$user_id)->where('id',$id)->delete();
        return yt_api_response([
            'status'=>true,
            'message'=>$delete ? 'Delete Successfully :)' : 'No address found.',
            'data'=>$delete
        ]);
    }

    // public function edit_address(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'id'=>'required',
    //         'name'=>'required',
    //         'phone'=>'required',
    //         'flat'=>'required',
    //         //'pincode'=>'required',
    //         'location'=>'required',
    //         'lat'=>'required',
    //         'lng'=>'required'
    //     ]);
    //     if ($validator->fails()) {
    //         $message = yt_validator_error_messages($validator);
    //         return yt_api_response(['status' => false,'message'=>$message]);
    //     }
    //     $user_id = rz_user_id($input);

    //     $lat=$input['lat'];
    //     $lng=$input['lng'];
    //     $location=rz_get_location_data_by_lat_long($lat,$lng);
    //     $results=$location['results'][0]['address_components'];
    //     $count_result=count($results);
    //     $pincode=$results[$count_result-1]['long_name'];
    //     $check=Location::active()->where('pincode',$pincode)->first();
    //     if($check){
    //         $id=$input['id'];
    //         unset($input['id']);
    //         $create = Address::where('id',$id)->where('user_id',$user_id)->update($input);
    //         return yt_api_response([
    //             'status'=>true,
    //             'message'=>$create ? 'Saved :)' : 'Address not saved please try again later.',
    //             'data'=>$create
    //         ]);
    //     }else{
    //         return yt_api_response([
    //             'status'=>false,
    //             'message'=>'You seems to be outside serviceable area please choose other pincodes from the list or try changing the address',
    //             'data'=>$check
    //         ]);
    //     }

    // }


    public function edit_address(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'=>'required',
            'name'=>'required',
            'phone'=>'required',
            'flat'=>'required',
            //'pincode'=>'required',
            'location'=>'required',
            // 'lat'=>'required',
            // 'lng'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id = rz_user_id($input);
        $id=$input['id'];
        unset($input['id']);
        $create = Address::where('id',$id)->where('user_id',$user_id)->update($input);
        return yt_api_response([
            'status'=>true,
            'message'=>$create ? 'Saved :)' : 'Address not saved please try again later.',
            'data'=>$create
        ]);

    }

    public function get_locations(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $fetch=Location::active()->get();
        return yt_api_response([
            'status'=>true,
            'message'=>$fetch ? 'fetch :)' : 'No data found.',
            'data'=>$fetch
        ]);
    }

    public function get_societies(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'location_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $fetch=Society::active()->where('location_id',$input['location_id'])->get();
        return yt_api_response([
            'status'=>true,
            'message'=>$fetch ? 'fetch :)' : 'No data found.',
            'data'=>$fetch
        ]);
    }
}

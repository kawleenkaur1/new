<?php

namespace App\Http\Controllers\API\DELIVERYBOY;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'phone'=>'required',
            'password'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }

        $mobile=$input['phone'];
        $user=User::where('phone',$mobile)->deliveryBoy()->where('status',1)->first();
        $password=($input['password']);
        if($user){
            if (Hash::check($password, $user->password)) {
                $d['device_id'] = $input['device_id'];
                $d['device_type'] = $input['device_type'];
                $d['device_token'] = $input['device_token'];
                $d['model_name'] = $input['model_name'];
                $update=User::where('id',$user->id)->update($d);
                $token =  $user->createToken('MyApp')->accessToken;
                return yt_api_response([
                    'status'=>true,
                    'user'=>$user,
                    'token'=>$token
                ]);
            }

        }

        return yt_api_response([
            'status'=>false,
            'message'=>'Invalid Credentials.',
        ]);

    }

}

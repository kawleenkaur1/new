<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CustomNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    //

    use CustomNotifications;
    public function recharge_wallet(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'amount'=>'required',
            'txn_id'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $cashback_perct = 10;

        $user_id=rz_user_id($input);
        $user=User::where('id',$user_id)->first();
        $old_wallet=$user->wallet;
        $amount =floatval($input['amount']);
        $updated_wallet = $old_wallet + $amount;
        $user->wallet=$updated_wallet;
        $user->save();
        $txn=[
            'user_id'=>$user_id,
            'txn_name'=>'Wallet Recharge',
            'payment_mode'=>'online',
            'txn_for'=>'wallet',
            'type'=>'credit',
            'old_wallet'=>$old_wallet,
            'txn_amount'=>$amount,
            'update_wallet'=>$updated_wallet,
            'status'=>1,
            'txn_mode'=>'other',
            'order_txn_id'=>$input['txn_id'],
            'created_at'=>$date=date('Y-m-d H:i:s'),
            'updated_at'=>$date,
            'wallet_type'=>1,
        ];
        $create = Transaction::create($txn);
        $this->wallet_txn_notification($user,$amount,'credit');


        // $cashback_old_wallet=$user->cashback_wallet;
        // $cashback_amount =round($amount*($cashback_perct/100));;
        // $cashback_updated_wallet = $cashback_old_wallet + $cashback_amount;
        // $user->cashback_wallet=$cashback_updated_wallet;
        // $user->save();

        // if(!empty(intval($cashback_amount))){
        //     $txn2=[
        //         'user_id'=>$user_id,
        //         'txn_name'=>'Cashback',
        //         'payment_mode'=>'online',
        //         'txn_for'=>'wallet',
        //         'type'=>'credit',
        //         'old_wallet'=>$old_wallet,
        //         'txn_amount'=>$amount,
        //         'update_wallet'=>$updated_wallet,
        //         'status'=>1,
        //         'txn_mode'=>'other',
        //         'order_txn_id'=>$input['txn_id'],
        //         'created_at'=>$date=date('Y-m-d H:i:s'),
        //         'updated_at'=>$date,
        //         'wallet_type'=>2,
        //     ];
        //     Transaction::create($txn2);
        // }
        // $cashback_price =round($amount*($cashback_perct/100));
       

        if($create){
            return yt_api_response(['status' => true,'message'=>'recharge successfully',
            'available_balance'=>$user->wallet,
            'user'=>$user
            ]);
        }

    }

    public function recharge_lists(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user=User::where('id',rz_user_id($input))->first();
        $list=Plan::active()->orderAsc()->get();
        return yt_api_response(['status' => true,'message'=>'fetch successfully',
            'available_balance'=>$user->wallet,
            'data'=>$list
            ]);
    }

    public function wallet_history(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'limit'=>'required',
            'offset'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $limit=isset($input['limit']) ? intval($input['limit']) : 10;
        $offset=isset($input['offset']) ? intval($input['offset']) : 0;
        $user_id=rz_user_id($input);
        $wallethistory=Transaction::orderBy('id','desc')->mainWallet()->where('user_id',$user_id)->limit($limit)->offset($offset)->walletTxn()->get();
        return yt_api_response([
            'status'=>true,
            'data'=>$wallethistory,
            'message'=>'.....'
        ]);
    }

    public function cashback_wallet_history(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($input, [
            'limit'=>'required',
            'offset'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $limit=isset($input['limit']) ? intval($input['limit']) : 10;
        $offset=isset($input['offset']) ? intval($input['offset']) : 0;
        $user_id=rz_user_id($input);
        $wallethistory=Transaction::orderBy('id','desc')->cashbackWallet()->where('user_id',$user_id)->limit($limit)->offset($offset)->get();
        return yt_api_response([
            'status'=>true,
            'data'=>$wallethistory,
            'message'=>'.....'
        ]);
    }
}

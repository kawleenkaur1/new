<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Returns;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CustomNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnsController extends Controller
{
    //
    use CustomNotifications;
    public function fetch_returnsorder(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Return Order';


          if (isset($_GET['get_export_data'])) {
             $data[] = array("#","OrderID","User","Product Name","QTY","Amount","Message","Status","Added");
               $users = Returns::orderDesc()->paginate();
               $i  = 1;
                foreach ($users as $item) {
                  
                       if($item->status == 1)
                       {
                        $st =   "Refunded";
                        }
                        else{
                              $st =  " ";
                        }
                       
                     

                    $data[] = array(
                      
                   "id"=>$i,
                   "order_id"=>$item->order_id,
                   "name"=>$item->user ? $item->user->name : '',
                   "product_name"=>$item->product_name,
                   "qty"=>$item->qty,
                   "price"=>$item->amount,
                   "issue"=>$item->issue,
                   "status"=>$st,
                   "created_at"=>date('d M y g:i A',strtotime($item->created_at)),
                   
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_returnsorder" . $string_file . ".csv");
                header("Pragma: no-cache");
                header("Expires: 0");

                $handle = fopen('php://output', 'w');

                foreach ($data as $data) {
                    fputcsv($handle, $data);
                }
                fclose($handle);
                exit;
    }



        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = Returns::
            where(function($query)  use ($q) {
                $query->where('order_id','LIKE', '%' . $q . '%');
                // ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                // ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                // ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                // ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
            })
            ->orderDesc()
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Returns::whereDate('created_at', $s_date)
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Returns::orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Returns::orderDesc()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.orders.returns',$template);
    }

    public function refund_for_return(Request $request,$id)
    {
        $validatedData = $request->validate([
            'amount' => 'required',
        ]);

        $input=$request->all();
        $amount=floatval($input['amount']);
        $msg=isset($input['message']) ? $input['message'] : '';
        $return=Returns::where('id',$id)->first();
        if($return->is_refunded == 0){
            $user=User::where('id',$return->user_id)->first();
            $old_wallet=$user->wallet;
            // DB::beginTransaction();
            // try {
                $updated_wallet = $old_wallet + $amount;
                $user->wallet=$updated_wallet;
                $user->save();
                $txn=[
                    'user_id'=>$user->id,
                    'payment_mode'=>'wallet',
                    'order_txn_id'=>time().$id.$user->id,
                    'type'=>'credit',
                    'old_wallet'=>$old_wallet,
                    'txn_amount'=>$amount,
                    'update_wallet'=>$updated_wallet,
                    'status'=>1,
                    'txn_for'=>'wallet',
                    'txn_mode'=>'other',
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $transaction = Transaction::create($txn);
                $return->txn_id=$transaction->id;
                $return->is_refunded=1;
                $return->status=1;
                $return->save();
                // DB::commit();
                $this->wallet_txn_notification($user,$amount,'credit',$msg);
                return redirect()->back()->with('success', 'Successfully done.');
            // }catch (\Exception $e) {
            //     DB::rollback();
            //     return redirect()->back()->with('warning', 'something went wrong.');
            // }
        }
        return redirect()->back()->with('warning', 'something went wrong.');

    }
}

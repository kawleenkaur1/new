<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function fetch_wallettxns(Request $request)
    {
        $page_limit =10;
        $template['page_title'] = 'Wallet Txns';

          if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("User","TxnId","Old wallet","Txn","Updated Wallet","Txn Type","Txn For","Status","Added");
                    if(isset($_GET['q']) && !empty($_GET['q'])){
                        $q = trim($_GET['q']);
                        $fetch = Transaction::newest()->walletTxn()
                        ->with('User')
                        ->where(function($query)  use ($q) {
                            $query->where('order_txn_id','LIKE', '%' . $q . '%')
                            ->orWhere('order_id','LIKE', '%' . $q . '%')
                            ->orWhere('type','LIKE', '%' . $q . '%')
                            ->orWhere('txn_amount','LIKE', '%' . $q . '%')
                            ->orWhereHas('User', function($query)  use ($q){
                                $query->where('name', $q)
                                ->orWhere('phone', $q);
                            });
                        })
                        ->paginate();
                        $fetch->appends (array ('q' => $q));
                    }else{
                        $fetch = Transaction::walletTxn()->newest()->paginate();
                    }
                $i = 1;
                foreach ($fetch as $user) {
                    $st="";
                       if ($user->status == 1)
                       {
                        $st ="Success";
                        }
                         else
                       {
                            if ($user->status == 2) {
                                $st = "Failed";
                            }
                            else{
                                $st = "Failed";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "user"=>$user->user ? $user->user->name : '',
                   "order_txn_id"=>$user->order_txn_id,
                   "old_wallet"=>$user->old_wallet,
                   "txn_amount"=>$user->txn_amount,
                   "update_wallet"=>$user->update_wallet,
                   "type"=>$user->type,
                   "txn_for"=>$user->txn_for,
                   "status"=>$st,
                         
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"wallettxns" . $string_file . ".csv");
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
            $fetch = Transaction::newest()->walletTxn()
            ->with('User')
            ->where(function($query)  use ($q) {
                $query->where('order_txn_id','LIKE', '%' . $q . '%')
                ->orWhere('order_id','LIKE', '%' . $q . '%')
                ->orWhere('type','LIKE', '%' . $q . '%')
                ->orWhere('txn_amount','LIKE', '%' . $q . '%')
                ->orWhereHas('User', function($query)  use ($q){
                    $query->where('name', $q)
                    ->orWhere('phone', $q);
                });
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Transaction::walletTxn()->newest()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.transaction.wallet',$template);
    }

    public function fetch_onlinetxns(Request $request)
    {
        $page_limit =10;
        $template['page_title'] = 'Online Txns';


        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("User","TxnId","Txn","Txn Type","Txn For","Status","Added");

               
              
                if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Transaction::newest()->onlineTxn()
                    ->with('User')
                    ->where(function($query)  use ($q) {
                        $query->where('order_txn_id','LIKE', '%' . $q . '%')
                        ->orWhere('order_id','LIKE', '%' . $q . '%')
                        ->orWhere('type','LIKE', '%' . $q . '%')
                        ->orWhere('txn_amount','LIKE', '%' . $q . '%')
                        ->orWhereHas('User', function($query)  use ($q){
                            $query->where('name', $q)
                            ->orWhere('phone', $q);
                        });
                    })
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Transaction::onlineTxn()->newest()->paginate();
                }
                $i = 1;
                foreach ($fetch as $user) {
                    $st="";
                       if ($user->status == 1)
                       {
                        $st ="Success";
                        }
                         else
                       {
                            if ($user->status == 2) {
                                $st = "Failed";
                            }
                            else{
                                $st = "Failed";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "user"=>$user->user ? $user->user->name : '',
                   "order_txn_id"=>$user->order_txn_id,
                   "txn_amount"=>$user->txn_amount,
                   "type"=>$user->type,
                   "txn_for"=>$user->txn_for,
                   "status"=>$st,
                         
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"onlinetxns" . $string_file . ".csv");
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
            $fetch = Transaction::newest()->onlineTxn()
            ->with('User')
            ->where(function($query)  use ($q) {
                $query->where('order_txn_id','LIKE', '%' . $q . '%')
                ->orWhere('order_id','LIKE', '%' . $q . '%')
                ->orWhere('type','LIKE', '%' . $q . '%')
                ->orWhere('txn_amount','LIKE', '%' . $q . '%')
                ->orWhereHas('User', function($query)  use ($q){
                    $query->where('name', $q)
                    ->orWhere('phone', $q);
                });
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Transaction::onlineTxn()->newest()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.transaction.online',$template);
    }

    public function fetch_walletusers(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Wallet';



        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("Name","Email","Phone","Wallet","Added");

               
                    if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = User::where('status',1)->customerUser()
            ->newUser()
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('phone','LIKE', '%' . $q . '%')
                ->orWhere('email','LIKE', '%' . $q . '%');
            })
            ->paginate();
            $users->appends (array ('search' => $q));
            $template['users'] = $users;
            return view('admin.users.customers', $template);
        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = User::where('status',1)->customerUser()->whereDate('created_at', $s_date)
                    ->newUser()
                    ->paginate();
                    $users->appends (array ('search_date' => $s_date));
                    $template['users'] = $users;
                    return view('admin.users.customers', $template);
                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = User::where('status',1)->customerUser()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->newUser()
                    ->paginate();
                    $users->appends (array ('search_date' => $date));
                    $template['users'] = $users;
                    return view('admin.users.customers', $template);
                }
            }
        }
        $users = User::customerUser()->newUser()->paginate();
                $i = 1;
                foreach ($users as $user) {
                   
                       
                    $data[] = array(
                      
                   "name"=>$user->name,
                   "email"=>$user->email,
                   "phone"=>$user->phone,
                   "wallet"=>$user->wallet,
                 
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"walletusers" . $string_file . ".csv");
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
            $users = User::where('status',1)->customerUser()
            ->newUser()
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('phone','LIKE', '%' . $q . '%')
                ->orWhere('email','LIKE', '%' . $q . '%');
            })
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));
            $template['users'] = $users;
            return view('admin.users.customers', $template);
        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = User::where('status',1)->customerUser()->whereDate('created_at', $s_date)
                    ->newUser()
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));
                    $template['users'] = $users;
                    return view('admin.users.customers', $template);
                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = User::where('status',1)->customerUser()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->newUser()
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                    $template['users'] = $users;
                    return view('admin.users.customers', $template);
                }
            }
        }
        $users = User::customerUser()->newUser()->paginate($page_limit);
        $template['users'] = $users;
        return view('admin.transaction.walletusers',$template);
    }
}

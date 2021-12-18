<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\CustomNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    use CustomNotifications;
    public function fetch_customers(Request $request)
    {
        $page_limit = 100;
        $template['page_title'] = 'Customers';

          if (isset($_GET['get_export_data'])) {
             $data[] = array("#","Name","Email","Phone","Wallet","Added","Status");
                $users = User::where('user_type',2)->newUser()->paginate();
              
                $i = 1;
                foreach ($users as $item) {
                  
                       if ($item->status == 1)
                       {
                        $st =   "Active";
                        }
                        else{
                            if ($item->status == 2) {
                                $st =   "Suspended";
                            }
                            else{
                                 $st =   "Inactive";
                            }
                        }
                     

                    $data[] = array(
                      
                   "id"=>$item->id,
                   "name"=>$item->name,
                   "email"=>$item->email,
                   "phone"=>$item->phone,
                   "wallet"=>$item->wallet,
                   "created_at"=>date('d M y g:i A',strtotime($item->created_at)),
                  
                   "status"=>$st,
                   
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"customers" . $string_file . ".csv");
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
            $users = User::where('status',1)->where('user_type',2)
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
                    $users = User::where('status',1)->where('user_type',2)->whereDate('created_at', $s_date)
                    ->newUser()
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));
                    $template['users'] = $users;
                    return view('admin.users.customers', $template);
                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = User::where('status',1)->where('user_type',2)
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->newUser()
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                    $template['users'] = $users;
                    return view('admin.users.customers', $template);
                }
            }
        }
        $users = User::where('user_type',2)->newUser()->paginate($page_limit);
        $template['users'] = $users;
        return view('admin.users.customers',$template);
    }



    public function get_user_details($id)
    {
        $data['user']= User::where('id',$id)->first();
        $data['address']=Address::where('user_id',$id)->default()->first();
        return View::make('admin.users.customer-view',$data)->render();
    }




    public function fetch_carts(Request $request)
    {
        $user_id = $_GET['user_id'];
        //
        $page_limit = 100;
        $template['page_title'] = 'Cart List';
        $users = DB::table('carts')->where('user_id',$user_id)->paginate($page_limit);
        // print_r( $users ); die;
        $template['users'] = $users;
        return view('admin.users.carts',$template);
    }
    public function fetch_list(Request $request)
    {
        $user_id = $_GET['user_id'];
        
        $page_limit = 100;
        $template['page_title'] = 'Wishlist List';
        $users = Wishlist::where('user_id',$user_id)->paginate($page_limit);
        $template['fetchdata'] = $users;
        return view('admin.users.wishlists',$template);
    }


     public function cod_user_disable($id)
    {
        // print_r($id); die;
        User::where('id',$id)->update(['cod'=>0]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }

     public function cod_user_enable($id)
    {
        // print_r($id); die;
        User::where('id',$id)->update(['cod'=>1]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }

    public function user_enable($user_id)
    {
        User::where('id',$user_id)->update(['status'=>1]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }

    public function user_disable($user_id)
    {
        User::where('id',$user_id)->update(['status'=>0]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }

    public function can_cash_hold_enable($user_id)
    {
        User::where('id',$user_id)->update(['can_cash_hold'=>1]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }

    public function can_cash_hold_disable($user_id)
    {
        User::where('id',$user_id)->update(['can_cash_hold'=>0]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }



    public function fetch_owners(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Owners';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = User::where('status',1)->where('user_type',3)
            ->newUser()
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('phone','LIKE', '%' . $q . '%')
                ->orWhere('email','LIKE', '%' . $q . '%');
            })
            ->paginate($page_limit);
            $users->appends (array ('q' => $q));
            $template['users'] = $users;
            return view('admin.users.owners', $template);
        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = User::where('status',1)->where('user_type',3)->whereDate('created_at', $s_date)
                    ->newUser()
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));
                    $template['users'] = $users;
                    return view('admin.users.owners', $template);
                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = User::where('status',1)->where('user_type',3)
                    ->newUser()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                    $template['users'] = $users;
                    return view('admin.users.owners', $template);
                }
            }
        }
        $users = User::where('user_type',3)->newUser()->paginate($page_limit);
        $template['users'] = $users;
        return view('admin.users.owners',$template);
    }

    public function add_owner(Request $request)
    {
        $template['page_title'] = 'Add Owner';
        $breadcrumb = [
            0=>[ 'title'=>'Owners List',
             'link'=>route('fetch_owners') ]
         ];
         $template['breadcrumb'] = $breadcrumb;
        return view('admin.users.add-owner',$template);
    }

    public function check_if_user_email_exists($email,$user_type=2,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('email',$email)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('email',$email)->where('user_type',$user_type)->first();

        }
        return $user;
    }
    public function check_if_user_phone_exists($phone,$user_type=2,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('phone',$phone)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('phone',$phone)->where('user_type',$user_type)->first();
        }
        return $user;
    }
    public function save_owner(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'email' => 'email',
            'status'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();
        $input['user_type'] = 3;
        $email = $input['email'];
        $phone = $input['phone'];
        if(!empty($email)){
            if($this->check_if_user_email_exists($email,3)){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }else{
            if($this->check_if_user_phone_exists($phone,3)){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/owner/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);
            $input['image'] = 'default.png';
        }
        unset($input['MAX_FILE_SIZE']);
        $create = User::create($input);
        if($create->id){
            return Redirect::route('fetch_owners')->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Owner not saved! Something went wrong! Please try again!');

    }

    public function edit_owner($id=0)
    {
        $template['page_title'] = 'Edit Owner';
        $breadcrumb = [
            0=>[ 'title'=>'Owners List',
             'link'=>route('fetch_owners') ]
         ];
         $template['breadcrumb'] = $breadcrumb;
        $template['user'] = User::where('id',$id)->where('user_type',3)->first();
        return view('admin.users.edit-owner',$template);
    }

    public function update_owner(Request $request,$id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'email' => 'email',
            'status'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();
        $input['user_type'] = 3;
        $email = $input['email'];
        $phone = $input['phone'];
        if(!empty($email)){

            if($this->check_if_user_email_exists($email,3,[$id])){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }else{
            if($this->check_if_user_phone_exists($phone,3,[$id])){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/owner/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);


        }
        unset($input['MAX_FILE_SIZE']);
        unset($input['_token']);
        $create = User::where('id',$id)->update($input);
        if($create){
            return Redirect::route('fetch_owners')->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Owner not saved! Something went wrong! Please try again!');

    }


    public function edit_admin_profile(Request $request)
    {
        $user = Auth::user();
        $user = User::where('id',$user->id)->first();
        // dd($user);
        $template['page_title'] = 'Edit Admin Profile';
        $template['admin'] = $user;
        return view('admin.users.admin-profile',$template);
    }

    public function update_admin_profile(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'email' => 'email',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
            'password' => 'required|confirmed|min:6'
        ]);
        $input = $request->all();
        $user = Auth::user();
        $user = User::where('id',$user->id)->first();

        if(!empty($input['email'])){
            if($this->check_if_user_email_exists($input['email'],1,[$user->id])){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }else{
            if($this->check_if_user_phone_exists($input['phone'],3)){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }
        if (Hash::check($input['old_password'], $user->password)) {
            $input['password'] = bcrypt($input['password']);
            if($_FILES['image']['size']>0){
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/user/'), $imageName);
                $input['image'] = $imageName;
            }else{
                unset($input['image']);
            }
            unset($input['_token']);
            unset($input['old_password']);
            unset($input['password_confirmation']);
            unset($input['MAX_FILE_SIZE']);


            $save = User::where('id',$user->id)->update($input);
            if ($save) {
                return redirect()->back()->with('success','Updated successfully');
            }
            return redirect()->back()->with('danger','Something went wrong! Please try again later.');
        } else {
            return redirect()->back()->with('danger','Old password does not match');
        }
    }


    public function wallet_recharge(Request $request,$id)
    {
        $validatedData = $request->validate([
            'amount' => 'required',
            'txn_type'=>'required'
        ]);

        $input=$request->all();
        $amount = $input['amount'];
        $msg=isset($input['message']) ? $input['message'] : '';
        unset($input['_token']);
        $user=User::where('id',$id)->first();
        $old_wallet=$user->wallet;
        if($input['txn_type'] == 2){ //debit

            if($old_wallet < $amount){
                return redirect()->back()->with('danger', 'user wallet is less than requested amount! Please try again with less than equal to user wallet!');
            }
            $updated_wallet = $old_wallet - $amount;
            $user->wallet=$updated_wallet;
            $user->save();
            $txn=[
                'user_id'=>$id,
                'payment_mode'=>'wallet',
                'order_txn_id'=>time().$id,
                'type'=>'debit',
                'old_wallet'=>$old_wallet,
                'txn_amount'=>$amount,
                'update_wallet'=>$updated_wallet,
                'status'=>1,
                'txn_for'=>'wallet',
                'txn_mode'=>'other',
                'created_at'=>$date=date('Y-m-d H:i:s'),
                'updated_at'=>$date
            ];
            $create = Transaction::create($txn);
            $this->wallet_txn_notification($user,$amount,'debit',$msg);
            return redirect()->back()->with('success', 'Successfully done.');
        }else{

            $updated_wallet = $old_wallet + $amount;
            $user->wallet=$updated_wallet;
            $user->save();
            $txn=[
                'user_id'=>$id,
                'payment_mode'=>'wallet',
                'order_txn_id'=>time().$id,
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
            $create = Transaction::create($txn);
            $this->wallet_txn_notification($user,$amount,'credit',$msg);

            return redirect()->back()->with('success', 'Successfully done.');
        }

        return redirect()->back()->with('danger', 'Something went wrong! Please try again!');

    }



}

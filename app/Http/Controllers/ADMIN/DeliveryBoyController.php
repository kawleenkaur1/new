<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;


class DeliveryBoyController extends Controller
{
    public function fetch_deliveryboys(Request $request)
    {
        $page_limit = 100;
        $template['page_title'] = 'Delivery Boys';


           if (isset($_GET['get_export_data'])) {
             $data[] = array("id","Name","Email","Phone","Img","Added","Status");
            $acounts = User::deliveryBoy()->paginate();
                $i = 1;
                foreach ($acounts as $user) {
                  
                       if ($user->status == 1)
                       {
                        $st =   "Active";
                        }
                         else
                       {
                            if ($user->status == 2) {
                                $st =   "Suspended";
                            }
                            else{
                                $st =   "Inactive";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "id"=>$user->id,
                   "name"=>$user->name,
                   "email"=>$user->email,
                   "phone"=>$user->phone,
                   "image_url"=>$user->image_url,
              
                   "status"=>$st,
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_deliveryboys" . $string_file . ".csv");
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
            $users = User::where('status',1)->deliveryBoy()
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('phone','LIKE', '%' . $q . '%')
                ->orWhere('email','LIKE', '%' . $q . '%');
            })
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = User::where('status',1)->deliveryBoy()->whereDate('created_at', $s_date)
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = User::where('status',1)->deliveryBoy()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = User::deliveryBoy()->paginate($page_limit);
        }
        $template['users'] = $users;
        return view('admin.users.deliveryboys',$template);
    }

     public function cash_PayOut_deliveryboys($id)
    {
        // print_r($id); die;
        DB::table('cashcollects')->where('deliveryboy_id',$id)->update(['status'=>1]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }



public function fetch_deliveries_by_deliveryboy($value='')
{
     $page_limit = 100;
        $template['page_title'] = 'Deliveryboy';


           if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("ORDERID","Name","PINCODE","Address","Amount","DeliveryBoy","Subs delivery Boy","Status","order_type","Payment Mode","Payment Status","Pending Amount","Added On");



    if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Order::
             where(function($query)  use ($q) {
                $query->where('orders.id','LIKE', '%' . $q . '%')
                ->orWhere('users.name','LIKE', '%' . $q . '%');
            })
            ->join('users', 'users.id', '=', 'orders.delivery_boy_id')
             ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $fetch = Order::
                    where(function($query)  use ($s_date) {
                        $query->whereDate('orders.created_at', $s_date);
                    })
                    ->join('users', 'users.id', '=', 'orders.delivery_boy_id')
                    ->paginate($page_limit);
                    $fetch->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $fetch = 
                    Order::where(function($query)  use ($s_date,$e_date) {
                        $query->whereBetween('orders.created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59']);
                    })
                    ->join('users', 'users.id', '=', 'orders.delivery_boy_id')
                    ->paginate($page_limit);

                    $fetch->appends (array ('search_date' => $date));
                }
            }
        }
        else{
            $fetch = Order::join('users', 'users.id', '=', 'orders.delivery_boy_id')
              ->paginate($page_limit);
        }
        
              //   $fetch = Order::join('users', 'users.id', '=', 'orders.delivery_boy_id')
              // ->paginate();
              
                $i = 1;
                foreach ($fetch as $item) {
                  
                       if ($item->status == 0)
                       {
                        $st =   "Pending";
                        }
                         elseif($item->status == 1)
                       {
                        $st =  "Confirmed";
                        }
                        elseif($item->status == 2)
                        {
                            $st = "Delivered";
                        }
                        else{
                            if ($item->is_refunded != 0) {
                               $st = "Cancelled & Refunded";
                            }
                            else{
                                $st = "Cancelled";
                            }
                        }
                        if ($item->order_type == 1) {
                            $order = "BuyOnce";
                        }
                        elseif ($item->order_type == 2) {
                            $order = "Subscribe";
                        }
                        if ($item->is_paid == 1) {
                            $is_paid = "PAID";
                        }
                        else  {
                            $is_paid = "NOT PAID";
                        }

                        if ($item->payment_mode == "online") {
                            $payment_mode = "online";
                        }
                        elseif ($item->payment_mode == "cod") {
                            $payment_mode = "cod";
                        }

                        elseif ($item->payment_mode == "wallet") {
                            $payment_mode = "wallet";
                        }

                    $data[] = array(
                      
                   "id"=>$item->id,
                   "name"=>$item->user->name,
                   "shipping_pincode"=>$item->shipping_pincode,
                   "shipping_location"=>$item->shipping_location,
                   "payable_amount"=>$item->payable_amount,
                   "deliveryboy"=>$item->deliveryboy ? $item->deliveryboy->name :'' ,
                   "subsdeliveryBoy"=>$item->subsdeliveryBoy ? $item->subsdeliveryBoy->name :'' ,
                   "status"=>$st,
                   "order_type"=>$order,
                   "payment_mode"=>$payment_mode,
                   "is_paid"=>$is_paid,
                   "pending_amount"=>$item->pending_amount,
                   "added"=>date('d M y g:i A',strtotime($item->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_deliveries_by_deliveryboy" . $string_file . ".csv");
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
            $fetch = Order::
             where(function($query)  use ($q) {
                $query->where('orders.id','LIKE', '%' . $q . '%')
                ->orWhere('users.name','LIKE', '%' . $q . '%');
            })
            ->join('users', 'users.id', '=', 'orders.delivery_boy_id')
             ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $fetch = Order::
                    where(function($query)  use ($s_date) {
                        $query->whereDate('orders.created_at', $s_date);
                    })
                    ->join('users', 'users.id', '=', 'orders.delivery_boy_id')
                    ->paginate($page_limit);
                    $fetch->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $fetch = 
                    Order::where(function($query)  use ($s_date,$e_date) {
                        $query->whereBetween('orders.created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59']);
                    })
                    ->join('users', 'users.id', '=', 'orders.delivery_boy_id')
                    ->paginate($page_limit);

                    $fetch->appends (array ('search_date' => $date));
                }
            }
        }
        else{
            $fetch = Order::join('users', 'users.id', '=', 'orders.delivery_boy_id')
              ->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.orders.delivery_boy_orders',$template);
}

    public function add_deliveryboy(Request $request)
    {
        $template['page_title'] = 'Add Delivery Boy';
        $breadcrumb = [
            0=>[ 'title'=>'Delivery Boys List',
             'link'=>route('fetch_deliveryboys') ]
         ];
         $template['warehouses'] = User::active()->warehouse()->get();
         $template['breadcrumb'] = $breadcrumb;
        return view('admin.users.add-deliveryboy',$template);
    }

    public function check_if_user_email_exists($email,$user_type=3,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('email',$email)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('email',$email)->where('user_type',$user_type)->first();

        }
        return $user;
    }
    public function check_if_user_phone_exists($phone,$user_type=3,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('phone',$phone)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('phone',$phone)->where('user_type',$user_type)->first();
        }
        return $user;
    }
    public function save_deliveryboy(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'password'=>'required',
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
        }
        if(!empty($phone)){
            if($this->check_if_user_phone_exists($phone,3)){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/deliveryboy/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);
        }

        if($_FILES['aadhar_image']['size']>0){
            $aadhar_imageName = time().'.'.$request->aadhar_image->extension();
            $request->aadhar_image->move(public_path('uploads/user/aadhar/'), $aadhar_imageName);
            $input['aadhar_image'] = $aadhar_imageName;
        }else{
            unset($input['aadhar_image']);
        }

        if($_FILES['pan_image']['size']>0){
            $pan_imageName = time().'.'.$request->pan_image->extension();
            $request->pan_image->move(public_path('uploads/user/pan/'), $pan_imageName);
            $input['pan_image'] = $pan_imageName;
        }else{
            unset($input['pan_image']);
        }

        if($_FILES['dl_image']['size']>0){
            $dl_imageName = time().'.'.$request->dl_image->extension();
            $request->dl_image->move(public_path('uploads/user/dl/'), $dl_imageName);
            $input['dl_image'] = $dl_imageName;
        }else{
            unset($input['dl_image']);
        }
        $input['password'] = bcrypt($input['password']);
        // dd($input);
        $create = User::create($input);
        if($create->id){
            return Redirect::route('fetch_deliveryboys')->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'User not saved! Something went wrong! Please try again!');

    }

    public function edit_deliveryboy($id=0)
    {
        $template['page_title'] = 'Edit Delivery Boy';
        $breadcrumb = [
            0=>[ 'title'=>'Delivery Boys List',
             'link'=>route('fetch_deliveryboys') ]
        ];
        // $template['cityadmins'] = User::active()->cityAdmin()->get();
        $template['warehouses'] = User::active()->warehouse()->get();
        $template['breadcrumb'] = $breadcrumb;
        $template['user'] = User::where('id',$id)->deliveryBoy()->first();
        return view('admin.users.edit-deliveryboy',$template);
    }

    public function update_deliveryboy(Request $request,$id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'email' => 'email',
            'status'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();
        $email = $input['email'];
        $phone = $input['phone'];
        if(!empty($input['password'])){
            $input['password'] = bcrypt($input['password']);
        }else{
            unset($input['password']);
        }
        if(!empty($email)){

            if($this->check_if_user_email_exists($email,3,[$id])){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }
        if(!empty($phone)){
            if($this->check_if_user_phone_exists($phone,3,[$id])){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/deliveryboy/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);
        }

        if($_FILES['aadhar_image']['size']>0){
            $aadhar_imageName = time().'.'.$request->aadhar_image->extension();
            $request->aadhar_image->move(public_path('uploads/user/aadhar/'), $aadhar_imageName);
            $input['aadhar_image'] = $aadhar_imageName;
        }else{
            unset($input['aadhar_image']);
        }

        if($_FILES['pan_image']['size']>0){
            $pan_imageName = time().'.'.$request->pan_image->extension();
            $request->pan_image->move(public_path('uploads/user/pan/'), $pan_imageName);
            $input['pan_image'] = $pan_imageName;
        }else{
            unset($input['pan_image']);
        }

        if($_FILES['dl_image']['size']>0){
            $dl_imageName = time().'.'.$request->dl_image->extension();
            $request->dl_image->move(public_path('uploads/user/dl/'), $dl_imageName);
            $input['dl_image'] = $dl_imageName;
        }else{
            unset($input['dl_image']);
        }
        unset($input['MAX_FILE_SIZE']);
        unset($input['_token']);
        $create = User::where('id',$id)->update($input);
        if($create){
            return Redirect::route('fetch_deliveryboys')->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'User not saved! Something went wrong! Please try again!');

    }
}

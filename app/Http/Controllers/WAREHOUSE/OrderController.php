<?php

namespace App\Http\Controllers\WAREHOUSE;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CustomNotifications;
use App\Traits\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Delivery as Deliveries;

use PDF;
class OrderController extends Controller
{

    use Delivery,CustomNotifications;
    public function fetch_orders(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Orders';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = Order::
            where(function($query)  use ($q) {
                $query->where('id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
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
                    $users = Order::whereDate('created_at', $s_date)
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Order::orderDesc()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.orders.orders',$template);
    }



     public function fetch_deliveryBoys_orders($id = 0)
    {
        $page_limit = 10;
        $template['page_title'] = 'DeliveryBoys Orders';
        $users = Order::orderDesc()->buydeliveryBoys($id)->paginate($page_limit);

        $template['fetchdata'] = $users;
        return view('admin.orders.orders',$template);
    }


    public function fetch_new_orderswarehouse(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Orders';
        $user = Auth::user();
        $id = $user->id;
        $user_data =  User::where('id',$id)->first();
        $ss = $user_data->pincode_allowance; 
        $aa =   explode('|',  $ss);
       // print_r($aa); die;
       // die;
          if (isset($_GET['get_export_data'])) {
             $data[] = array("ORDERID","Name","PINCODE","Address","Amount","DeliveryBoy","Subs delivery Boy","Status","order_type","Payment Mode","Payment Status","Pending Amount","Added On");
              $acounts = Order::where('status',0)->whereIn('shipping_pincode',$aa)->orderDesc()->paginate();

                $i = 1;
                foreach ($acounts as $item) {

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
                header("Content-Disposition: attachment; filename=\"fetch_buyonce_orders" . $string_file . ".csv");
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
            $users = Order::where('status',0)->whereIn('shipping_pincode',$aa)->
            where(function($query)  use ($q) {
                $query->where('id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
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
                    $users = Order::where('status',0)->whereIn('shipping_pincode',$aa)->whereDate('created_at', $s_date)
                    
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::where('status',0)->whereIn('shipping_pincode',$aa)->orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{

            $users = Order::where('status',0)->whereIn('shipping_pincode',$aa)->orderDesc()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;


        return view('warehouse.orders.orders',$template);
    }


    public function fetch_buyonce_orderswarehouse(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Orders';
         $user = Auth::user();
        $id = $user->id;
          if (isset($_GET['get_export_data'])) {
             $data[] = array("ORDERID","Name","PINCODE","Address","Amount","DeliveryBoy","Subs delivery Boy","Status","order_type","Payment Mode","Payment Status","Pending Amount","Added On");
              $acounts = Order::where('warehouse_id',$id)->orderDesc()->paginate();

                $i = 1;
                foreach ($acounts as $item) {

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
                header("Content-Disposition: attachment; filename=\"fetch_buyonce_orders" . $string_file . ".csv");
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
            $users = Order::
            where(function($query)  use ($q) {
                $query->where('id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
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
                    $users = Order::where('warehouse_id',$id)->whereDate('created_at', $s_date)
                    
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::where('warehouse_id',$id)->orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Order::where('warehouse_id',$id)->orderDesc()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('warehouse.orders.orders',$template);
    }


    public function fetch_pending_orderswarehouse(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Pending Orders';
         $user = Auth::user();
        $id = $user->id;
          if (isset($_GET['get_export_data'])) {
             $data[] = array("ORDERID","Name","PINCODE","Address","Amount","DeliveryBoy","Subs delivery Boy","Status","order_type","Payment Mode","Payment Status","Pending Amount","Added On");
              $acounts = Order::where('warehouse_id',$id)->orderDesc()->pending()->paginate();

                $i = 1;
                foreach ($acounts as $item) {

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
                header("Content-Disposition: attachment; filename=\"fetch_buyonce_orders" . $string_file . ".csv");
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
            $users = Order::
            where(function($query)  use ($q) {
                $query->where('warehouse_id',$id)->where('id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
            })
            ->pending()
            ->orderDesc()
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Order::where('warehouse_id',$id)->whereDate('created_at', $s_date)
                    ->pending()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::where('warehouse_id',$id)->orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->pending()
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Order::where('warehouse_id',$id)->orderDesc()
            ->pending()
            ->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('warehouse.orders.orders',$template);
    }





    public function fetch_subscribe_orders(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Orders';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = Order::
            where(function($query)  use ($q) {
                $query->where('id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
            })
            ->subscribe()
            ->orderDesc()
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Order::whereDate('created_at', $s_date)
                    ->subscribe()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->subscribe()
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Order::orderDesc()->subscribe()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.orders.orders',$template);
    }

    public function fetch_completedorderswarehouse(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Completed Orders';
        $user = Auth::user();
        $id = $user->id;
         if (isset($_GET['get_export_data'])) {
             $data[] = array("ORDERID","Name","PINCODE","Address","Amount","DeliveryBoy","Subs delivery Boy","Status","order_type","Payment Mode","Payment Status","Pending Amount","Added On");
               $acounts = Order::where('warehouse_id',$id)->orderDesc()->completed()->paginate();

                $i = 1;
                foreach ($acounts as $item) {

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
                header("Content-Disposition: attachment; filename=\"fetch_completedorders" . $string_file . ".csv");
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
            $users = Order::
            completed()
            ->where(function($query)  use ($q) {
                $query->where('warehouse_id',$id)->where('id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
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
                    $users = Order::where('warehouse_id',$id)->whereDate('created_at', $s_date)
                    ->completed()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::where('warehouse_id',$id)->orderDesc()
                    ->completed()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Order::where('warehouse_id',$id)->orderDesc()->completed()->paginate($page_limit);
        }
        $template['reset_link']=route('fetch_completedorders');
        $template['fetchdata'] = $users;
        return view('warehouse.orders.orders',$template);
    }

    public function fetch_cancelledorderswarehouse(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Cancelled Orders';

         if (isset($_GET['get_export_data'])) {
             $data[] = array("ORDERID","Name","PINCODE","Address","Amount","DeliveryBoy","Subs delivery Boy","Status","order_type","Payment Mode","Payment Status","Pending Amount","Added On");
               $acounts = Order::orderDesc()->cancelled()->paginate();


                $i = 1;
                foreach ($acounts as $item) {

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
                header("Content-Disposition: attachment; filename=\"fetch_cancelledorders" . $string_file . ".csv");
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
            $users = Order::
            cancelled()
            ->where(function($query)  use ($q) {
                $query->where('id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
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
                    $users = Order::whereDate('created_at', $s_date)
                    ->cancelled()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::orderDesc()
                    ->cancelled()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Order::orderDesc()->cancelled()->paginate($page_limit);
        }
        $template['reset_link']=route('fetch_cancelledorders');
        $template['fetchdata'] = $users;
        return view('warehouse.orders.orders',$template);
    }

    public function load_order_datawarehouse($id)
    {
        $data['order']= Order::where('id',$id)->first();
        $check_if_have_subscription=OrderHistory::where('order_id',$id)->subscriptions()->first();
        $data['check_if_have_subscription']=$check_if_have_subscription;
        return View::make('warehouse.orders.view-details',$data)->render();
    }

    public function load_order_item_data($id)
    {
        $data['order']= OrderHistory::where('id',$id)->first();

        return View::make('admin.orders.single-order',$data)->render();
    }

    public function load_assign_deliveryBoy_datawarehouse($id)
    {

         $user = Auth::user();
        $ide = $user->id;

        $data['deliveryBoy'] = User::where('warehouse_id',$ide)->deliveryBoy()->where('is_online',1)->get();
        // $data['order']=$order=Order::where('id',$id)->first();
        $check_if_have_subscription=OrderHistory::where('order_id',$id)->subscriptions()->first();
        $check_if_have_buyonce=OrderHistory::where('order_id',$id)->buyonce()->first();

        $data['check_if_have_subscription']=$check_if_have_subscription;
        $data['check_if_have_buyonce']=$check_if_have_buyonce;

        if($check_if_have_subscription){
            $data['sbs_deliveryBoy'] = User::deliveryBoy()->get();
        }


        $data['order_id']=$id;
        return View::make('warehouse.orders.assignDelivery',$data)->render();

    }

    public function assign_deliveryBoywarehouse(Request $request,$id)
    {
        if(empty($id)){
            return redirect()->back()->with('danger','Something went wrong');
        }
        $request->validate([
            // 'delivery_boy_id'=>'required',
        ]);
        $input = $request->all();

        $check = Deliveries::where('order_id',$id)->where('status',1)->orderBy('id','desc')->first();
        $order=Order::where('id',$id)->first();

        if(!$check){
            // Deliveries::where('order_id',$id)->where('status',0)->update([
            //     'status'=>2
            // ]);

            $delivery_user = User::where('id',$input['delivery_boy_id'])->first();

            // $checkdel = Deliveries::where('order_id',$id)->where('status',0)->where('delivery_boy_id',$input['delivery_boy_id'])->first();
            // if($checkdel){
            //     $checkdel->status=2;
            //     $checkdel->save();
            // }

            Deliveries::where('order_id',$id)->delete();
            // $delivery_boy = User::where('id',$input['delivery_boy_id'])->first();
            $delivery = Deliveries::create([
                'user_id'=>$order->user_id,
                'warehouse_id'=>$delivery_user->warehouse_id,
                'delivery_boy_id'=>$input['delivery_boy_id'],
                'order_id'=>$order->id,
                'status'=>0,
                'created_at'=>$date=date('Y-m-d H:i:s'),
                'updated_at'=>$date
            ]);
            $update = Order::where('id',$id)->update(['assign_time'=>date('Y-m-d H:i:s'),'warehouse_id'=>$delivery_user->warehouse_id,'delivery_boy_id'=>intval(isset($input['delivery_boy_id'])?$input['delivery_boy_id']:0),'status'=>1,'deliver_boy_subscription_id'=>intval(isset($input['deliver_boy_subscription_id']) ?$input['deliver_boy_subscription_id']:0 )]);
            if($update){
                // $user=User::where('id',$order->user_id)->first();
                // $this->assign_deliveryboy_notification($user,$order,[]);
                return redirect()->back()->with('success','assign successfully to order #'.$id);
            }
            return redirect()->back()->with('danger','Something went wrong');
        }else{
            $check1 = Deliveries::where('order_id',$id)->where('status',0)->orderBy('id','desc')->first();
            if($check1){
                return redirect()->back()->with('danger','Already Assigned');
            }else{
                return redirect()->back()->with('danger','Something went wrong');
            }
        }

       
    }

    public function cancel_order(Request $request,$id)
    {
        if(empty($id)){
            return redirect()->back()->with('danger','Something went wrong');
        }
        $request->validate([
            'cancel_reason'=>'required'
        ]);
        $input = $request->all();
        DB::beginTransaction();
        try {
            $update = Order::where('id',$id)->update(['cancel_reason'=>$input['cancel_reason'],'status'=>4]);
            $order=Order::where('id',$id)->first();
            $o_histories=OrderHistory::where('order_id',$id)->get();
            $deliveries=Deliveries::where('order_id',$id)->count();
            if(count($o_histories)){
                foreach($o_histories as $c){
                    $p= Product::where('id',$c->product_id)->first();
                    $old_stock=intval($p->stock);
                    $s_qty=$c->qty;
                    $update_stock=$old_stock+$s_qty;
                    if($update_stock<0){
                        $update_stock=0;
                    }
                    $p->stock=$update_stock;
                    $p->save();
                }


            }
            $order_history=OrderHistory::where('order_id',$id)->update(['status'=>4]);

            $user=User::where('id',$order->user_id)->first();
            $old_wallet=$user->wallet;
            if($order->payment_mode=='wallet' || $order->payment_mode=='online'){
                $refund_amount=0;
                if($order->order_type==2){
                    if($deliveries<$order->payable_deliveries_count){
                        $refund_deliveries=$order->payable_deliveries_count-$deliveries;
                        $refund_amount=round($order->paid_amount/$refund_deliveries);
                    }
                }else{
                    $refund_amount=$order->paid_amount;
                }


               if(!empty($refund_amount)){
                    $updated_wallet = $old_wallet + $refund_amount;
                    $user->wallet=$updated_wallet;
                    $user->save();

                    $txn=[
                        'user_id'=>$user->id,
                        'payment_mode'=>'wallet',
                        'order_id'=>$order->id,
                        'order_txn_id'=>time().$order->id.$user->id,
                        'type'=>'credit',
                        'old_wallet'=>$old_wallet,
                        'txn_amount'=>$refund_amount,
                        'update_wallet'=>$updated_wallet,
                        'status'=>1,
                        'txn_for'=>'refund',
                        'txn_mode'=>'other',
                        'created_at'=>$date=date('Y-m-d H:i:s'),
                        'updated_at'=>$date
                    ];
                    $transaction = Transaction::create($txn);
                    $order->is_refunded=$transaction->id;
                    // $order->cancel_at = date('Y-m-d H:i:s');
                    $order->save();
                }


            }
            $order->cancel_at = date('Y-m-d H:i:s');
            $order->save();

                // if($order->order_type==2){

                // }
            DB::commit();

            $this->cancel_order_by_admin_notification($user,$order);
            if(isset($refund_amount) && !empty($refund_amount)){
                // $msg="You have got your ";
                $this->wallet_txn_notification($user,$refund_amount,'credit');
            }
            return redirect()->back()->with('success','Order #'.$id.' cancelled successfully.');
        }catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger','Something went wrong');
        }

    }

    public function stop_subscription(Request $request,$id)
    {
        if(empty($id)){
            return redirect()->back()->with('danger','Something went wrong');
        }
        $request->validate([
            // 'cancel_reason'=>'required'
        ]);
        $input = $request->all();
        // $update = Order::where('id',$id)->update(['status'=>2,'is_paid'=>1,'completed_at'=>date('Y-m-d H:i:s')]);
        $order_history=OrderHistory::where('order_id',$id)->update(['status'=>3]);
        // $this->cancel_order_by_admin_notification($user,$order);
        // if(isset($refund_amount) && !empty($refund_amount)){
        //     // $msg="You have got your ";
        //     $this->wallet_txn_notification($user,$refund_amount,'credit');
        // }
        return redirect()->back()->with('success','Successfully Stopped.');

    }

    public function cancel_subscription(Request $request,$id)
    {
        if(empty($id)){
            return redirect()->back()->with('danger','Something went wrong');
        }
        $request->validate([
            // 'cancel_reason'=>'required'
        ]);
        $input = $request->all();
        // $update = Order::where('id',$id)->update(['status'=>2,'is_paid'=>1,'completed_at'=>date('Y-m-d H:i:s')]);
        $order_history=OrderHistory::where('order_id',$id)->update(['status'=>4]);
        // $this->cancel_order_by_admin_notification($user,$order);
        // if(isset($refund_amount) && !empty($refund_amount)){
        //     // $msg="You have got your ";
        //     $this->wallet_txn_notification($user,$refund_amount,'credit');
        // }
        return redirect()->back()->with('success','Successfully Stopped.');

    }

    public function delivered_orderwarehouse(Request $request,$id)
    {
        if(empty($id)){
            return redirect()->back()->with('danger','Something went wrong');
        }
        $request->validate([
            // 'cancel_reason'=>'required'
        ]);
        $input = $request->all();
        $update = Order::where('id',$id)->update(['status'=>2,'is_paid'=>1,'completed_at'=>date('Y-m-d H:i:s')]);
        $order_history=OrderHistory::where('order_id',$id)->update(['status'=>2]);
        if($update){
            $order=Order::where('id',$id)->first();
            if($order->payment_mode=='cod'){
                $txn=[
                    'user_id'=>$order->user_id,
                    'payment_mode'=>'cod',
                    'order_id'=>$order->id,
                    'order_txn_id'=>time().$order->user_id,
                    'type'=>'debit',
                    'old_wallet'=>0,
                    'txn_amount'=>$order->payable_amount,
                    'update_wallet'=>0,
                    'status'=>1,
                    'txn_mode'=>'cod',
                    'txn_for'=>'order',
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $create = Transaction::create($txn);
            }
            $user=User::where('id',$order->user_id)->first();
            $this->order_complete_notification($user,$order);
            return redirect()->back()->with('success','Done.');
        }
        return redirect()->back()->with('danger','Something went wrong');
    }


    public function complete_subs_order(Request $request,$id)
    {
        if(empty($id)){
            return redirect()->back()->with('danger','Something went wrong');
        }
        $request->validate([
            // 'cancel_reason'=>'required'
        ]);
        $input = $request->all();
        $order = Order::where('id',$id)->first();
        $update = Order::where('id',$id)->update(['status'=>2,'is_paid'=>1,'completed_at'=>date('Y-m-d H:i:s')]);
        $order_history=OrderHistory::where('order_id',$id)->where('order_type',2)->update(['status'=>2]);
        if($update){
            $order=Order::where('id',$id)->first();
            if($order->payment_mode=='cod'){
                $txn=[
                    'user_id'=>$order->user_id,
                    'payment_mode'=>'cod',
                    'order_id'=>$order->id,
                    'order_txn_id'=>time().$order->user_id,
                    'type'=>'debit',
                    'old_wallet'=>0,
                    'txn_amount'=>$order->payable_amount,
                    'update_wallet'=>0,
                    'status'=>1,
                    'txn_mode'=>'cod',
                    'txn_for'=>'order',
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $create = Transaction::create($txn);
            }
            $user=User::where('id',$order->user_id)->first();
            $this->order_complete_notification($user,$order);
            return redirect()->back()->with('success','Done.');
        }
        return redirect()->back()->with('danger','Something went wrong');
    }



    public function order_invoicewarehouse($order_id)
    {
        $order=Order::where('id',$order_id)->first();
        $data['order']=$order;
        $data['yt_app_settings']=Setting::first();
        $data['check_if_have_subscription']=0;
        view()->share('employee',$data);
        // $pdf = PDF::loadView('admin.orders.view-details', $data);
        $pdf = PDF::loadView('mails.order', $data);


        // download PDF file with download method
        return $pdf->download('pdf_file_'.time().'.pdf');
    }

    public function refund_for_order(Request $request,$id)
    {
        $validatedData = $request->validate([
            'amount' => 'required',
        ]);

        $input=$request->all();
        $amount=floatval($input['amount']);
        $msg=isset($input['message']) ? $input['message'] : '';
        $order=Order::where('id',$id)->first();
        if($order->is_refunded == 0 && ($order->status == 3 || $order->status == 4)){
            $user=User::where('id',$order->user_id)->first();
            $old_wallet=$user->wallet;
            // DB::beginTransaction();
            // try {
                $updated_wallet = $old_wallet + $amount;
                $user->wallet=$updated_wallet;
                $user->save();
                $txn=[
                    'user_id'=>$user->id,
                    'payment_mode'=>'wallet',
                    'order_id'=>$order->id,
                    'order_txn_id'=>time().$order->id.$user->id,
                    'type'=>'credit',
                    'old_wallet'=>$old_wallet,
                    'txn_amount'=>$amount,
                    'update_wallet'=>$updated_wallet,
                    'status'=>1,
                    'txn_for'=>'refund',
                    'txn_mode'=>'other',
                    'created_at'=>$date=date('Y-m-d H:i:s'),
                    'updated_at'=>$date
                ];
                $transaction = Transaction::create($txn);
                $order->is_refunded=$transaction->id;
                $order->save();
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


    // public function mark_today_delivered($id)
    // {
    //     $check
    // }


}

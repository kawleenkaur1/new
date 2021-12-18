<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Frequency;
use App\Models\OrderHistory;
use App\Traits\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    //

    use Delivery;
    public function fetch_subscriptions(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Subscriptions';



          if (isset($_GET['get_export_data'])) {
             $data[] = array("#","OrderID","Product","QTY","Amount","Deliveries","Start Date","Delivery","End Date","Name","Pincode","Status");
              $users = OrderHistory::orderDesc()->subscriptions()->paginate();
              
                $i = 1;
                foreach ($users as $item) {
                  
                       if ($item->order->status == 0)
                       {
                        $st =   "Pending";
                        }
                         elseif($item->order->status == 1)
                       {
                        $st =  "Confirmed";
                        }
                        elseif($item->order->status == 2)
                        {
                            $st = "Delivered";
                        }
                        else
                        {
                            $st = "Cancelled";
                        }
                     

                    $data[] = array(
                      
                   "id"=>$item->id,
                   "order_id"=>$item->order_id,
                   "product"=>$item->product ? $item->product->name.' '.$item->actual_qty.' '.$item->unit : '',
                   "qty"=>$item->qty,
                   "price"=>$item->price,
                   "deliveries"=>$item->deliveries,
                   "start_date"=>date('d M Y',strtotime($item->start_date)),
                   "skip_days"=> $item->skip_days,
                   "end_date"=>date('d M Y',strtotime($item->additionals['end_date'])),
                   "shipping_name"=>$item->shipping_name,
                   "shipping_pincode"=>$item->shipping_pincode,
                   "shipping_pincode"=>$item->order->subsdeliveryBoy ? $item->order->subsdeliveryBoy->name :'',






                   // "deliveryboy"=>$item->deliveryboy ? $item->deliveryboy->name :'' ,
                   // "subsdeliveryBoy"=>$item->subsdeliveryBoy ? $item->subsdeliveryBoy->name :'' ,
                   "status"=>$st,
                   
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_subscriptions" . $string_file . ".csv");
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
            $users = OrderHistory::
            where(function($query)  use ($q) {
                $query->where('order_id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('skip_days','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
            })
            // ->whereNotIn('status',[2,3,4])
            ->subscriptions()
            ->orderDesc()
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = OrderHistory::whereDate('start_date', $s_date)
                    ->subscriptions()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = OrderHistory::orderDesc()
                    ->subscriptions()
                    ->where('start_date','>=',$s_date.' 00:00:00')
                    ->where('end_date','<=',$e_date.' 23:59:59')
                    // ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }elseif(isset($_GET['frequency']) && !empty($_GET['frequency'])){
            $q=trim($_GET['frequency']);
            $users = OrderHistory::
            where(function($query)  use ($q) {
                $query->where('skip_days','LIKE', '%' . $q . '%');
            })
            // ->whereNotIn('status',[2,3,4])
            ->subscriptions()
            ->orderDesc()
            ->paginate($page_limit);
            $users->appends (array ('frequency' => $q));
        }
        else{
            $users = OrderHistory::orderDesc()->subscriptions()
            ->paginate($page_limit);
        }
        $template['frequencies']=Frequency::get();
        $template['fetchdata'] = $users;
        return view('admin.orders.subscriptions',$template);
    }


    public function fetch_buyonces(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Buy Once Orders';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = OrderHistory::
            where(function($query)  use ($q) {
                $query->where('order_id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
            })
            ->buyOnce()
            ->orderDesc()
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }elseif(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = OrderHistory::whereDate('created_at', $s_date)
                    ->buyOnce()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = OrderHistory::orderDesc()
                    ->buyOnce()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = OrderHistory::orderDesc()->buyOnce()
            ->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.orders.buyonces',$template);
    }


    public function fetch_quantity_orders(Request $request)
    {
        $page_limit = 1000;
        $template['page_title'] = 'Buy Day Orders 09:00 AM to 09:00 PM';


           if (isset($_GET['get_export_data'])) {
             $data[] = array("Product","Unit");

              $users = DB::select("select qty, product_id,unit,id,order_id 
from `order_histories` where `created_at` between '".date('Y-m-d')." 09:00:00' and '".date('Y-m-d')." 21:00:00'  ");
    if ($users) 
    {
         $product_id = array(); 
        foreach ($users as $my_object) {
            $product_id[] = $my_object->product_id; //any object field
        }

        array_multisort($product_id, SORT_ASC, $users);
         $p = $users[0]->product_id;
         $unit = 0;
         $ii = 0;
         $array = array();
         $aa = 1;
        foreach ($users as $v) {
                  
                    
             if($p == $v->product_id) {
                    
                      $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit += $u[0]* $v->qty;  
                    } else { 
                         $unit += $v->unit* $v->qty;
                    }
                   
                } else {
                    $unit = 0;
                     $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit = $u[0];  
                    } else { 
                         $unit = $v->unit;
                    }
                    $unit = $unit * $v->qty;
                }
                $array[] = array('product_id'=>$v->product_id,'unit'=>$unit);
                
                 $p = $v->product_id;

                 $aa++;
                 
        }
      
    }
     else{
        $array=array();
    }
    // print_r($array);die;
               for ($i=0;$i< count($array); $i++)
               {
                if($array[$i]['product_id'] != @$array[$i+1]['product_id'] ) {

                     $product_id = $array[$i]['product_id'];
                                     $product = DB::select("select * 
                                        from `products` where id =  $product_id");

                    $data[] = array(
                    "name"=>$product[0]->name,
                    "unit"=>$array[$i]['unit'],
                  
                  
                    );
                   
                }
            }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"Buy Day Orders 09:00 AM to 09:00 PM" . $string_file . ".csv");
                header("Pragma: no-cache");
                header("Expires: 0");

                $handle = fopen('php://output', 'w');

                foreach ($data as $data) {
                    fputcsv($handle, $data);
                }
                fclose($handle);
                exit;
    }




        // print_r("expression"); die;
        $users = DB::select("select qty, product_id,unit,id,order_id 
from `order_histories` where `created_at` between '".date('Y-m-d')." 09:00:00' and '".date('Y-m-d')." 21:00:00'  ");
    if ($users) 
    {
         $product_id = array(); 
        foreach ($users as $my_object) {
            $product_id[] = $my_object->product_id; //any object field
        }

        array_multisort($product_id, SORT_ASC, $users);
         $p = $users[0]->product_id;
         $unit = 0;
         $ii = 0;
         $array = array();
         $aa = 1;
        foreach ($users as $v) {
                  
                    
             if($p == $v->product_id) {
                    
                      $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit += $u[0]* $v->qty;  
                    } else { 
                         $unit += $v->unit* $v->qty;
                    }
                   
                } else {
                    $unit = 0;
                     $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit = $u[0];  
                    } else { 
                         $unit = $v->unit;
                    }
                    $unit = $unit * $v->qty;
                }
                $array[] = array('product_id'=>$v->product_id,'unit'=>$unit);
                
                 $p = $v->product_id;

                 $aa++;
                 
        }
      
    }
     else{
        $array=array();
    }
       
      
       
                 
        $template['fetchdata'] = $array;
       // print_r($template['fetchdata']); die;
        return view('admin.orders.quantity_orders',$template);
    }

    public function quantity_orders_next_day(Request $request)
    {
      


         $page_limit = 1000;
        $template['page_title'] = 'Buy Night Orders 09:00 PM to 09:00 AM';


          if (isset($_GET['get_export_data'])) {
             $data[] = array("Product","Unit");

               $edate = date('Y-m-d',strtotime('Y-m-d' . ' +1 day')).' 09:00:00';
        // print_r("expression"); die;
         $users = DB::select("select qty, product_id,unit,id,order_id 
from `order_histories` where `created_at` between '".date('Y-m-d')." 21:00:00' and 
'".$edate."'  ");
    if ($users) 
    {
         $product_id = array(); 
        foreach ($users as $my_object) {
            $product_id[] = $my_object->product_id; //any object field
        }

        array_multisort($product_id, SORT_ASC, $users);
         $p = $users[0]->product_id;
         $unit = 0;
         $ii = 0;
         $array = array();
         $aa = 1;
        foreach ($users as $v) {
                  
                    
             if($p == $v->product_id) {
                    
                      $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit += $u[0]* $v->qty;  
                    } else { 
                         $unit += $v->unit* $v->qty;
                    }
                   
                } else {
                    $unit = 0;
                     $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit = $u[0];  
                    } else { 
                         $unit = $v->unit;
                    }
                    $unit = $unit * $v->qty;
                }
                $array[] = array('product_id'=>$v->product_id,'unit'=>$unit);
                
                 $p = $v->product_id;

                 $aa++;
                 
        }
      
    }
     else{
        $array=array();
    }
    // print_r($array);die;
               for ($i=0;$i< count($array); $i++)
               {
                if($array[$i]['product_id'] != @$array[$i+1]['product_id'] ) {

                     $product_id = $array[$i]['product_id'];
                                     $product = DB::select("select * 
                                        from `products` where id =  $product_id");

                    $data[] = array(
                    "name"=>$product[0]->name,
                    "unit"=>$array[$i]['unit'],
                  
                  
                    );
                   
                }
            }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"Buy Night Orders 09:00 PM to 09:00 AM" . $string_file . ".csv");
                header("Pragma: no-cache");
                header("Expires: 0");

                $handle = fopen('php://output', 'w');

                foreach ($data as $data) {
                    fputcsv($handle, $data);
                }
                fclose($handle);
                exit;
    }



        $edate = date('Y-m-d',strtotime('Y-m-d' . ' +1 day')).' 09:00:00';
        // print_r("expression"); die;
         $users = DB::select("select qty, product_id,unit,id,order_id 
from `order_histories` where `created_at` between '".date('Y-m-d')." 21:00:00' and 
'".$edate."'  ");
    

    if ($users) 
    {
         $product_id = array(); 
        foreach ($users as $my_object) {
            $product_id[] = $my_object->product_id; //any object field
        }

        array_multisort($product_id, SORT_ASC, $users);
         $p = $users[0]->product_id;
         $unit = 0;
         $ii = 0;
         $array = array();
         $aa = 1;
        foreach ($users as $v) {
                  
                    
             if($p == $v->product_id) {
                    
                      $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit += $u[0]* $v->qty;  
                    } else { 
                         $unit += $v->unit* $v->qty;
                    }
                   
                } else {
                    $unit = 0;
                     $u = explode(" ",$v->unit);
                    if(count($u)>0) {
                           $unit = $u[0];  
                    } else { 
                         $unit = $v->unit;
                    }
                    $unit = $unit * $v->qty;
                }
                $array[] = array('product_id'=>$v->product_id,'unit'=>$unit);
                
                 $p = $v->product_id;

                 $aa++;
                 
        }
    }
    else{
        $array=array();
    }
       
      
                 
        $template['fetchdata'] = $array;
        return view('admin.orders.quantity_orders_next_day',$template);
    }






    public function todays_tomorrows_buyonce_delivery(Request $request)
    {

        if(isset($_GET['tomorrow']) && $_GET['tomorrow'] == true){
            $date=date('Y-m-d',strtotime("+1 days"));
            $template['page_title'] = 'Tomorrow\'s Buy Once Delivery';
        }else{
            $date=date('Y-m-d');
            $template['page_title'] = 'Today\'s Buy Once Delivery';
        }

        $page_limit = 10;

        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = OrderHistory::
            where(function($query)  use ($q) {
                $query->where('order_id','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_name','LIKE', '%' . $q . '%')
                ->orWhere('shipping_phone','LIKE', '%' . $q . '%')
                ->orWhere('shipping_pincode','LIKE', '%' . $q . '%');
            })
            ->whereDate('created_at', $date)
            ->buyOnce()
            ->orderDesc()
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }else{
            $users = OrderHistory::orderDesc()->buyOnce()
            ->whereDate('created_at', $date)
            ->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.orders.buyonces',$template);
    }


    public function today_deliveries(Request $request)
    {
        // zero-config_wrapper
        $date=date('Y-m-d');
        $arr=$this->today_deliveries_trait();
        $buyonce=$arr['buyonce'];
        $subscriptions=$arr['subscriptions'];
        $template['page_title'] = 'Today\'s Buy Once Delivery';
        $template['page_title_2'] = 'Today\'s Subscription Delivery';
        $template['date_custom']=$date;
        $template['buyonces'] = $buyonce;
        $template['subscriptions'] = $subscriptions;

        return view('admin.orders.buyOnceplusSubs',$template);
    }

    public function tomorrows_deliveries(Request $request)
    {
        // zero-config_wrapper
        $date=date('Y-m-d', strtotime(' +1 day'));
        $arr=$this->tomorrow_deliveries_trait();
        $buyonce=$arr['buyonce'];
        $subscriptions=$arr['subscriptions'];
        $template['page_title'] = 'Tomorrow\'s Buy Once Delivery';
        $template['page_title_2'] = 'Tomorrow\'s Subscription Delivery';
        $template['date_custom']=$date;

        $template['buyonces'] = $buyonce;
        $template['subscriptions'] = $subscriptions;

        return view('admin.orders.buyOnceplusSubs',$template);
    }

}

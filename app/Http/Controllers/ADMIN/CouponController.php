<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CouponController extends Controller
{
    //
    public function create_coupon()
    {
        $template['page_title'] = 'Create Coupon';
        $breadcrumb = [
            0=>[ 'title'=>'Coupons',
             'link'=>route('fetch_coupons') ]
         ];
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.coupon.create',$template);
    }

    public function fetch_coupons()
    {
        $d['page_title'] = 'Coupons';
        $page_limit = 10;



        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("Name","Type","Discount","Position","Status","Added");

               
                 if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Coupon::where('status','!=',2)->orderBy('id','desc')
                    ->where(function($query)  use ($q) {
                        $query->where('name','LIKE', '%' . $q . '%');
                    })
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Coupon::where('status','!=',2)->orderBy('id','desc')->paginate();
                }
           
                $i = 1;
                foreach ($fetch as $user) {
                    $st="";
                       if ($user->status == 1)
                       {
                        $st ="Active";
                        }
                         else
                       {
                            if ($user->status == 2) {
                                $st = "Deleted";
                            }
                            else{
                                $st = "Disable";
                            }
                       
                        }
                         if ($user->type == 1)
                       {
                        $type ="Percentage";
                        }
                         else
                       {
                        $type = "Flat";
                        }
                       
                    $data[] = array(
                      
                   "name"=>$user->name,
                   "type"=>$type,
                   "discount"=>$user->discount,
                   "position"=>$user->position,
                   "status"=>$st,
                         
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_coupons" . $string_file . ".csv");
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
            $fetch = Coupon::where('status','!=',2)->orderBy('id','desc')
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%');
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Coupon::where('status','!=',2)->orderBy('id','desc')->paginate($page_limit);
        }
        $d['fetchdata'] = $fetch;
        return view('admin.coupon.index',$d);
    }


    public function save_coupon(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:coupons',
            'type'=>'required',
            'discount'=>'required',
            'use_limit'=>'required',
            'status'=>'required',
            'validity'=>'required'
        ]);
        $input = $request->all();
        $input['use_limit']=intval($input['use_limit']);
        $input['position']=intval($input['position']);
        $input['discount']=floatval($input['discount']);
        $input['max_discount']=floatval(isset($input['max_discount']) ? $input['max_discount'] : 0);
        $input['min_order_amount']=floatval(isset($input['min_order_amount']) ? $input['min_order_amount'] : 0);

            $validity=explode('to',$input['validity']);
            if(count($validity)>0){
                $input['starts_on'] = date('Y-m-d H:i:s',strtotime(trim($validity[0])));
                $input['expires_on'] = date('Y-m-d H:i:s',strtotime(trim($validity[1])));
            }
            unset($input['validity']);
        $create = Coupon::create($input);
        if($create->id){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Coupon not saved! Something went wrong! Please try again!');
    }

    public function edit_coupon($id)
    {
        $template['page_title'] = 'Edit Coupon';
        $breadcrumb = [
            0=>[ 'title'=>'Coupons',
             'link'=>route('fetch_coupons') ]
         ];
        $template['breadcrumb'] = $breadcrumb;
        $template['coupon'] = $coupon = Coupon::where('id',$id)->first();
        if(!$coupon){
            return Redirect::route('fetch_coupons')->with('warning','Coupon not found');
        }
        return view('admin.coupon.edit',$template);
    }

    public function update_coupon(Request $request,$id)
    {
        $request->validate([
            'type'=>'required',
            'discount'=>'required',
            'use_limit'=>'required',
            'status'=>'required',
            'validity'=>'required'
        ]);
        $input = $request->all();
        $input['use_limit']=intval($input['use_limit']);
        $input['position']=intval($input['position']);
        $input['discount']=floatval($input['discount']);
        $input['max_discount']=floatval($input['max_discount']);
        $validity=explode('to',$input['validity']);
        if(count($validity)>0){
            $input['starts_on'] = date('Y-m-d H:i:s',strtotime(trim($validity[0])));
            $input['expires_on'] = date('Y-m-d H:i:s',strtotime(trim($validity[1])));
        }
            unset($input['_token']);
            unset($input['validity']);

        $create = Coupon::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Coupon not saved! Something went wrong! Please try again!');
    }

    public function delete_coupon($id)
    {
        $create = Coupon::where('id',$id)->update(['status'=>2]);
        if($create){
            return redirect()->back()->with('success', 'Successfully Deleted!');

        }
        return redirect()->back()->with('danger', 'Coupon not Deleted! Something went wrong! Please try again!');
    }

    public function fetch_couponhistory(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Coupon History';



        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("OrderID","Name","Coupon","Added");

               $users = Order::orderDesc()->couponHistory()->notCancel()->paginate($page_limit);
                // print_r($users); die;
                $i = 1;
                foreach ($users as $item) {
                   
                    $data[] = array(
                      
                   "id"=>$item->id,
                   "name"=>$item->user ? $item->user->name : '',
                   "coupon"=>$item->coupon ? $item->coupon->name : '',
                   "added"=>date('d M y g:i A',strtotime($item->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"couponhistory" . $string_file . ".csv");
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
                $query->where('id','LIKE', '%' . $q . '%');
            })
            ->orderDesc()
            ->couponHistory()
            ->notCancel()
            ->paginate($page_limit);
            $users->appends (array ('search' => $q));

        }elseif(isset($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Order::whereDate('created_at', $s_date)
                    ->couponHistory()
                    ->notCancel()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Order::orderDesc()
                    ->couponHistory()
                    ->notCancel()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Order::orderDesc()->couponHistory()->notCancel()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.coupon.coupon_history',$template);
    }

}

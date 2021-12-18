<?php

namespace App\Http\Controllers\WAREHOUSE;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Society;
use App\Models\User;
use App\Traits\GLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WarehouseController extends Controller
{
    use GLocation;
    public function fetch_warehouses(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Warehouses';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = User::warehouse()
            ->orderBy('id','desc')
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
                    $users = User::warehouse()->whereDate('created_at', $s_date)
                    ->orderBy('id','desc')
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = User::warehouse()
                    ->orderBy('id','desc')
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = User::warehouse()
            ->orderBy('id','desc')
            ->paginate($page_limit);
        }
        $template['users'] = $users;
        return view('admin.users.warehouses',$template);
    }


    public function add_warehouse(Request $request)
    {
        $template['page_title'] = 'Add Warehouse';
        $breadcrumb = [
            0=>[ 'title'=>'Warehouse List',
             'link'=>route('fetch_warehouses') ]
         ];

         $pincodes1 = Location::active()->pluck('pincode')->toArray();
        //  $pincodes1 = Society::active()->pluck('pincode')->toArray();
         $template['pincodes'] = $pincodes1;
         $template['breadcrumb'] = $breadcrumb;
        return view('admin.users.addeditwarehouse',$template);
    }

    public function check_if_user_email_exists($email,$user_type=5,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('email',$email)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('email',$email)->where('user_type',$user_type)->first();

        }
        return $user;
    }
    public function check_if_user_phone_exists($phone,$user_type=5,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('phone',$phone)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('phone',$phone)->where('user_type',$user_type)->first();
        }
        return $user;
    }
    public function save_warehouse(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'email' => 'email',
            'status'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();
        $input['user_type'] = 5;
        $email = $input['email'];
        $phone = $input['phone'];
        if(!empty($email)){
            if($this->check_if_user_email_exists($email,5)){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }
        if(!empty($phone)){
            if($this->check_if_user_phone_exists($phone,5)){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/warehouse/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);
            $input['image'] = 'default.png';
        }
        unset($input['MAX_FILE_SIZE']);
        // dd($input);
        $input['password'] = bcrypt($input['password']);
        $input['pincode_allowance'] = implode('|',$input['pincode_allowance']);
        $lat=trim($input['latitude']);
        $lon=trim($input['longitude']);
        $lc=$this->get_pincode_and_address($lat,$lon);
        if(!empty($lc)){
            $input['pincode']=$lc['pincode'];
        }

        $create = User::create($input);
        if($create->id){
            return Redirect::route('fetch_warehouses')->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'User not saved! Something went wrong! Please try again!');

    }

    public function edit_warehousewarehouse($id=0)
    {
        $template['page_title'] = 'Edit Warehouse';
        $breadcrumb = [
            0=>[ 'title'=>'Warehouse List',
             'link'=>route('fetch_warehouses') ]
         ];
         $template['breadcrumb'] = $breadcrumb;
         $pincodes1 = Location::active()->pluck('pincode')->toArray();

         $template['pincodes'] = $pincodes1;

        $template['user'] = User::where('id',$id)->warehouse()->first();
        return view('warehouse.users.addeditwarehouse',$template);
    }

    public function update_warehousewarehouse(Request $request,$id)
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

            if($this->check_if_user_email_exists($email,5,[$id])){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }
        if(!empty($phone)){
            if($this->check_if_user_phone_exists($phone,5,[$id])){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/warehouse/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);
        }
        $lat=trim($input['latitude']);
        $lon=trim($input['longitude']);
        // echo $lat.'  - '.$lon;die;
        $lc=$this->get_pincode_and_address($lat,$lon);
        if(!empty($lc)){
            $input['pincode']=$lc['pincode'];
        }
        // $input['pincode_allowance'] = implode('|',$input['pincode_allowance']);


        unset($input['MAX_FILE_SIZE']);
        unset($input['_token']);
        $create = User::where('id',$id)->update($input);
        if($create){
             return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'User not saved! Something went wrong! Please try again!');

    }

}

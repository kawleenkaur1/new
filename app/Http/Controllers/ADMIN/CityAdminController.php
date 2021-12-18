<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CityAdminController extends Controller
{
    public function fetch_cityadmins(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'City Admins';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $users = User::cityAdmin()
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
                    $users = User::cityAdmin()->whereDate('created_at', $s_date)
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = User::cityAdmin()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = User::cityAdmin()->paginate($page_limit);
        }
        $template['users'] = $users;
        return view('admin.users.cityadmins',$template);
    }


    public function add_cityadmin(Request $request)
    {
        $template['page_title'] = 'Add City Admin';
        $breadcrumb = [
            0=>[ 'title'=>'City Admin List',
             'link'=>route('fetch_cityadmins') ]
         ];
         $template['breadcrumb'] = $breadcrumb;
        return view('admin.users.add-cityadmin',$template);
    }

    public function check_if_user_email_exists($email,$user_type=4,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('email',$email)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('email',$email)->where('user_type',$user_type)->first();

        }
        return $user;
    }
    public function check_if_user_phone_exists($phone,$user_type=4,$whereNotIn=[])
    {
        if(!empty($whereNotIn)){
            $user = User::where('phone',$phone)->whereNotIn('id',$whereNotIn)->where('user_type',$user_type)->first();;
        }else{
            $user = User::where('phone',$phone)->where('user_type',$user_type)->first();
        }
        return $user;
    }
    public function save_cityadmin(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'pincode'=>'required',
            'password'=>'required',
            'email' => 'email',
            'status'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();
        $input['user_type'] = 4;
        $email = $input['email'];
        $phone = $input['phone'];
        if(!empty($email)){
            if($this->check_if_user_email_exists($email,4)){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }
        if(!empty($phone)){
            if($this->check_if_user_phone_exists($phone,4)){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/cityadmin/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);
            $input['image'] = 'default.png';
        }
        unset($input['MAX_FILE_SIZE']);
        // dd($input);
        $input['password'] = bcrypt($input['password']);

        $create = User::create($input);
        if($create->id){
            return Redirect::route('fetch_cityadmins')->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'User not saved! Something went wrong! Please try again!');

    }

    public function edit_cityadmin($id=0)
    {
        $template['page_title'] = 'Edit City Admin';
        $breadcrumb = [
            0=>[ 'title'=>'City Admin List',
             'link'=>route('fetch_cityadmins') ]
         ];
         $template['breadcrumb'] = $breadcrumb;
        $template['user'] = User::where('id',$id)->cityAdmin()->first();
        return view('admin.users.edit-cityadmin',$template);
    }

    public function update_cityadmin(Request $request,$id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'pincode'=>'required',
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

            if($this->check_if_user_email_exists($email,4,[$id])){
                return redirect()->back()->with('warning', 'Email already exists!');
            }
        }
        if(!empty($phone)){
            if($this->check_if_user_phone_exists($phone,4,[$id])){
                return redirect()->back()->with('warning', 'Phone already exists!');
            }
        }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/user/cityadmin/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);


        }
        unset($input['MAX_FILE_SIZE']);
        unset($input['_token']);
        $create = User::where('id',$id)->update($input);
        if($create){
            return Redirect::route('fetch_cityadmins')->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'User not saved! Something went wrong! Please try again!');

    }

}

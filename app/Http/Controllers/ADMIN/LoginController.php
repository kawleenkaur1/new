<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function login_page()
    {
        if(Auth::check()){
            return Redirect::route('admin_dashboard');
        }
        $d['page_title'] = 'Admin Login';
        return view('admin.layouts.login_layout',$d);
    }

    public function admin_login_request(Request $request){

        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 1,
            'status'=>1
            ])
        ){
            return Redirect::route('admin_dashboard')->with('success', 'Successfully Logged In!');
        }
        return Redirect::route('admin_login')->with('danger', 'Invalid Credentials');;
    }

    public function logout() {
        Auth::logout();
        // return redirect('/login');
        return Redirect::route('admin_login');
    }
}

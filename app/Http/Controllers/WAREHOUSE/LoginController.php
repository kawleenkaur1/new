<?php

namespace App\Http\Controllers\WAREHOUSE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function warehouse_login_page()
    {
        if(Auth::user() && Auth::user()->user_type === 5){
            return Redirect::route('warehouse_dashboard');

        }

        $d['page_title'] = 'Warehouse Login';
        return view('warehouse.layouts.login_layout',$d);
    }

    public function warehouse_login_request(Request $request){

        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 5,
            'status'=>1
            ])
        ){
            return Redirect::route('warehouse_dashboard')->with('success', 'Successfully Logged In!');
        }
        return Redirect::route('warehouse_login')->with('danger', 'Invalid Credentials');;
    }

    public function warehouseLogout() {
        Auth::logout();
        // return redirect('/login');
        return Redirect::route('warehouseLogout');
    }
}

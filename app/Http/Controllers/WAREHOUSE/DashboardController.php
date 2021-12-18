<?php

namespace App\Http\Controllers\WAREHOUSE;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {   error_reporting(0);
        $user = Auth::user();
        $id = $user->id;
        $date = date('Y-m-d');
        $template['total_deliveryboy'] = User::where('warehouse_id',$id)->deliveryBoy()->count();
        $template['new_deliveryboy'] = User::where('warehouse_id',$id)->deliveryBoy()->whereDate('created_at',$date)->count();


        $template['total_orders'] = Order::where('warehouse_id',$id)->count();
        $template['new_orders'] = Order::where('warehouse_id',$id)->whereDate('created_at',$date)->count();


        $template['total_onging_orders'] = Order::where('warehouse_id',$id)->where('warehouse_id',$id)->pending()->count();
        $template['new_onging_orders'] = Order::where('warehouse_id',$id)->whereDate('created_at',$date)->pending()->count();


        $template['total_complete_orders'] = Order::where('warehouse_id',$id)->where('warehouse_id',$id)->completed()->count();
        $template['new_complete_orders'] = Order::where('warehouse_id',$id)->whereDate('created_at',$date)->completed()->count();


        $template['total_cancelled_orders'] = Order::where('warehouse_id',$id)->where('warehouse_id',$id)->cancelled()->count();
        $template['new_cancelled_orders'] = Order::where('warehouse_id',$id)->whereDate('created_at',$date)->cancelled()->count();


        // print_r($template['total_deliveryboy']); die;

        $template['page_title'] = "Dashboard";
        return view('warehouse.dashboard.index',$template);
    }
}

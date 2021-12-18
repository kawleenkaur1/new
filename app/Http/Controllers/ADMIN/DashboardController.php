<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {   error_reporting(0);

        $date = date('Y-m-d');
        $template['total_customers'] = User::customerUser()->count();
        $template['new_customers'] = User::customerUser()->whereDate('created_at',$date)->count();

        $template['total_products'] = Product::where('status','!=',2)->count();
        $template['new_products'] = Product::where('status','!=',2)->whereDate('created_at',$date)->count();

        $template['total_orders'] = Order::count();
        $template['new_orders'] = Order::whereDate('created_at',$date)->count();

        $template['total_subscriptions'] = OrderHistory::subscriptions()->count();
        $template['new_subscriptions'] = OrderHistory::subscriptions()->whereDate('created_at',$date)->count();

        $template['total_buyonce'] = OrderHistory::buyOnce()->count();
        $template['new_buyonce'] = OrderHistory::buyOnce()->whereDate('created_at',$date)->count();

        $template['total_categories'] = Category::active()->count();
        $template['new_categories'] = Category::active()->whereDate('created_at',$date)->count();

        $template['total_cancel_orders'] = Order::cancelled()->count();
        $template['total_new_cancel'] = Order::cancelled()->whereDate('updated_at',$date)->count();

        $template['total_complete_orders'] = Order::completed()->count();
        $template['total_new_complete'] = Order::completed()->whereDate('updated_at',$date)->count();
        // $template['total_enquiries'] = Enquiry::count();
        // $template['new_enquiries'] = Enquiry::whereDate('created_at',$date)->count();
        // $template['total_vehicles'] =Vehicle::whereIn('status',[0,1])->count();
        // $template['new_vehicles'] =Vehicle::whereIn('status',[0,1])->whereDate('created_at',$date)->count();
        // $template['total_subscriptions'] = Subscription::count();
        // $template['new_subscriptions'] = Subscription::whereDate('created_at',$date)->count();
        $template['page_title'] = "Dashboard";
        return view('admin.dashboard.index',$template);
        // print_r("expression"); die;
    }
}

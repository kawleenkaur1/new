<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    //

    public function fetch_wishlists(Request $request)
    {
        $page_limit=10;
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Wishlist::orderBy('id','desc')
            ->with('Product')
            ->with('User')
            ->where(function($query)  use ($q) {
                $query->WhereHas('Product', function($query)  use ($q){
                    $query->where('name', $q)
                    ->orWhere('id',$q);
                })
                ->orWhereHas('User', function($query)  use ($q){
                    $query->where('name', $q)
                    ->orWhere('phone',$q)
                    ->orWhere('email',$q);
                });
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Wishlist::orderBy('id','desc')->paginate($page_limit);
        }
        $template['page_title']='Wishlists';
        $template['fetchdata'] = $fetch;
        return view('admin.report.wishlists',$template);
    }

    public function fetch_referrals(Request $request)
    {
        $page_limit=10;
        $fetch = Referral::orderBy('id','desc')->paginate($page_limit);
        $template['page_title']='Referrals';
        $template['fetchdata'] = $fetch;
        // dd($fetch);
        return view('admin.report.referrals',$template);
    }
}

<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Models\Career;
use Illuminate\Http\Request;

class CareersController extends Controller
{

    public function fetch_supports(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Support';
        if(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Support::whereDate('created_at', $s_date)
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Support::orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Support::orderDesc()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.support.index',$template);
    }

    public function mark_as_solved($id)
    {

        $support=Support::where('id',$id)->first();
        if(!$support){
            return redirect()->back()->with('warning', 'something went wrong.');
        }
        $support->status=1;
        $support->save();

        return redirect()->back()->with('success', 'Successfully done.');
    }


    public function fetch_pending_supports(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Support';
        if(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Support::whereDate('created_at', $s_date)
                    ->pending()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Support::orderDesc()
                    ->pending()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Support::orderDesc()->pending()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.support.index',$template);
    }


    public function fetch_completed_supports(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Support';
        if(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Support::whereDate('created_at', $s_date)
                    ->completed()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Support::orderDesc()
                    ->completed()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Support::orderDesc()->completed()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.support.index',$template);
    }
}

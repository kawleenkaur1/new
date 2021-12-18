<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Models\Collaborates;
use Illuminate\Http\Request;

class CollaboratesController extends Controller
{

    public function fetch_collaborates(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Collaborates';
        if(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Collaborates::whereDate('created_at', $s_date)
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Collaborates::orderDesc()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Collaborates::orderDesc()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.collaborates.index',$template);
    }

    public function mark_as_solved_collaborates($id)
    {

        $support=Collaborates::where('id',$id)->first();
        if(!$support){
            return redirect()->back()->with('warning', 'something went wrong.');
        }
        $support->status=1;
        $support->save();

        return redirect()->back()->with('success', 'Successfully done.');
    }


    public function fetch_pending_collaborates(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Collaborates';
        if(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Collaborates::whereDate('created_at', $s_date)
                    ->pending()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Collaborates::orderDesc()
                    ->pending()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Collaborates::orderDesc()->pending()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.collaborates.index',$template);
    }


    public function fetch_completed_collaborates(Request $request)
    {
        $page_limit = 10;
        $template['page_title'] = 'Collaborates';
        if(isset($_GET['search_date']) && !empty($_GET['search_date'])){
            $date = trim($_GET['search_date']);
            if(!empty($date)){
                $arr = explode('to',$date);
                if(count($arr) === 1){
                    $s_date = trim($arr[0]);
                    $users = Collaborates::whereDate('created_at', $s_date)
                    ->completed()
                    ->orderDesc()->paginate($page_limit);
                    $users->appends (array ('search_date' => $s_date));

                }elseif(count($arr) > 1){
                    $s_date = trim($arr[0]);
                    $e_date = trim($arr[1]);
                    $users = Collaborates::orderDesc()
                    ->completed()
                    ->whereBetween('created_at', [$s_date.' 00:00:00',$e_date.' 23:59:59'])
                    ->paginate($page_limit);
                    $users->appends (array ('search_date' => $date));
                }
            }
        }else{
            $users = Collaborates::orderDesc()->completed()->paginate($page_limit);
        }
        $template['fetchdata'] = $users;
        return view('admin.collaborates.index',$template);
    }
}

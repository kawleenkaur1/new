<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FaqController extends Controller
{
    //


    public function fetch_faqs(Request $request)
    {

        $d['page_title'] = 'FAQ\'s';
        $page_limit = 10;
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = FAQ::where('status','!=',2)->orderBy('id','desc')
            ->where(function($query)  use ($q) {

                $query->where('question','LIKE', '%' . $q . '%');
                // ->orWhere('type','LIKE', '%' . ($type) . '%');
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = FAQ::where('status','!=',2)->orderBy('id','desc')->paginate($page_limit);
        }
        $d['fetchdata'] = $fetch;
        return view('admin.faq.index',$d);
    }

    public function save_faq(Request $request)
    {
        $request->validate([
            'question'=>'required',
            'answer'=>'required'
        ]);
        $input = $request->all();

        $create = FAQ::create($input);
        if($create->id){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'FAQ not saved! Something went wrong! Please try again!');
    }

    public function load_faq_data($id)
    {
        $data['faq']= FAQ::where('id',$id)->first();
        return View::make('admin.faq.faq-form',$data)->render();
    }

    public function edit_faq(Request $request,$id)
    {
        $request->validate([
            'question'=>'required',
            'answer'=>'required'
        ]);

        $input = $request->all();

        unset($input['_token']);

        $create = FAQ::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Banner not saved! Something went wrong! Please try again!');

    }

    public function delete_faq($id)
    {
        FAQ::where('id',$id)->delete();
        return redirect()->back()->with('warning','Deleted successfully!');
    }
}

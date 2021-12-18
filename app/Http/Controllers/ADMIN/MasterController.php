<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\UNIT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MasterController extends Controller
{
    //


    public function fetch_units(Request $request)
    {

        $d['page_title'] = 'Unit\'s';
        $page_limit = 10;
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = UNIT::where('status','!=',2)->orderBy('id','desc')
            ->where(function($query)  use ($q) {

                $query->where('name','LIKE', '%' . $q . '%');
                // ->orWhere('type','LIKE', '%' . ($type) . '%');
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = UNIT::where('status','!=',2)->orderBy('id','desc')->paginate($page_limit);
        }
        $d['fetchdata'] = $fetch;
        return view('admin.unit.index',$d);
    }

    public function save_unit(Request $request)
    {
        $request->validate([
            'name'=>'required',
        ]);
        $input = $request->all();

        $create = UNIT::create($input);
        if($create->id){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'unit not saved! Something went wrong! Please try again!');
    }

    public function load_unit_data($id)
    {
        $data['unit']= UNIT::where('id',$id)->first();
        return View::make('admin.unit.unit-form',$data)->render();
    }

    public function edit_unit(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
        ]);

        $input = $request->all();

        unset($input['_token']);

        $create = UNIT::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Banner not saved! Something went wrong! Please try again!');

    }

    public function delete_unit($id)
    {
        UNIT::where('id',$id)->delete();
        return redirect()->back()->with('warning','Deleted successfully!');
    }
}

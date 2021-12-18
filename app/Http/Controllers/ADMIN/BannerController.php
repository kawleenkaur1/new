<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class BannerController extends Controller
{

    public function fetch_banners(Request $request)
    {
        $d['page_title'] = 'Banners';
        $page_limit = 10;
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Banner::where('status','!=',2)->orderBy('position','asc')
            ->where(function($query)  use ($q) {
                if($q == 'top'){
                    $type = 1;
                }elseif($q=='bottom'){
                    $type = 2;
                }else{
                    $type = $q;
                }
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('type','LIKE', '%' . ($type) . '%');
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Banner::where('status','!=',2)->orderBy('position','asc')->paginate($page_limit);
        }
        // $d['categories'] = Category::active()->orderBy('name','asc')->get();
        $d['fetchdata'] = $fetch;
        return view('admin.banner.banners',$d);
    }

    public function add_banner()
    {
        $template['page_title'] = 'Add Banner';

        $breadcrumb = [
            0=>[ 'title'=>'Banners',
             'link'=>route('fetch_banners')]
         ];
         $template['categories'] = Category::active()->orderBy('name','asc')->get();
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.banner.addedit',$template);
    }

    public function update_banner($id)
    {
        $template['page_title'] = 'Edit Banner';

        $breadcrumb = [
            0=>[ 'title'=>'Banners',
             'link'=>route('fetch_banners')]
        ];
        $template['category']=$banner= Banner::where('id',$id)->first();
        $template['categories'] = Category::active()->orderBy('name','asc')->get();
        $template['subcategories'] =Subcategory::active()->where('category_id',$banner->link_parent_id)->orderBy('name','asc')->get();
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.banner.addedit',$template);
    }


    public function save_banner(Request $request)
    {
        $request->validate([
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);
        $input = $request->all();

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/banners/'), $imageName);
            $input['image'] = $imageName;
        }else{
            $input['image'] = 'default.png';
        }


        $category_id=$input['category_id'];
        $subcategory_id=1;
        if(!empty($category_id) && !empty($subcategory_id)){
            $input['link_type']=3;
            $input['link_id']=$subcategory_id;
            $input['link_parent_id']=$category_id;

        }elseif(!empty($category_id)){
            $input['link_type']=2;
            $input['link_id']=$category_id;
        }else{
            $input['link_type']=0;
            $input['link_id']=0;
        }

        unset($input['category_id']);
        unset($input['subcategory_id']);

        $create = Banner::create($input);
        if($create->id){
            return Redirect::route('update_banner',['id'=>$create->id])->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Banner not saved! Something went wrong! Please try again!');
    }

    public function edit_banner(Request $request,$id)
    {
        $request->validate([
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/banners/'), $imageName);
            $input['image'] = $imageName;
        }
        unset($input['_token']);
        $category_id=$input['category_id'];
        $subcategory_id=1;
        if(!empty($category_id) && !empty($subcategory_id)){
            $input['link_type']=3;
            $input['link_id']=$subcategory_id;
            $input['link_parent_id']=$category_id;
        }elseif(!empty($category_id)){
            $input['link_type']=2;
            $input['link_id']=$category_id;
        }else{
            $input['link_type']=0;
            $input['link_id']=0;
        }

        unset($input['category_id']);
        unset($input['subcategory_id']);
        $create = Banner::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Banner not saved! Something went wrong! Please try again!');

    }

    public function load_banner_data($id)
    {
        $data['category']=$banner= Banner::where('id',$id)->first();
        $data['categories'] = Category::active()->orderBy('name','asc')->get();
        $data['subcategories'] =Subcategory::active()->where('category_id',$banner->link_parent_id)->orderBy('name','asc')->get();
        return View::make('admin.banner.banner-form',$data)->render();
    }

    public function delete_banner($id)
    {
        Banner::where('id',$id)->update(['status'=>2]);
        return redirect()->back()->with('warning','Deleted successfully!');
    }
}

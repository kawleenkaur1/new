<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class CategoryController extends Controller
{
    public function fetch_categories(Request $request)
    {

        $d['page_title'] = 'Categories';
        $page_limit = 10;


           if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("id","Name","Image","Status","Position","Added");

              if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Category::where('status','!=',2)->orderAsc()
                    ->where(function($query)  use ($q) {
                        $query->where('name','LIKE', '%' . $q . '%');
                    })
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Category::where('status','!=',2)->orderAsc()->paginate();
                }

           
                $i = 1;
                foreach ($fetch as $user) {
                    $st="";
                       if ($user->status == 1)
                       {
                        $st ="Active";
                        }
                         else
                       {
                            if ($user->status == 2) {
                                $st = "Deleted";
                            }
                            else{
                                $st = "Disable";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "id"=>$user->id,
                   "name"=>$user->name,
                   "image_url"=>$user->image_url,
                   "status"=>$st,
                   "position"=>$user->position,              
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_categories" . $string_file . ".csv");
                header("Pragma: no-cache");
                header("Expires: 0");

                $handle = fopen('php://output', 'w');

                foreach ($data as $data) {
                    fputcsv($handle, $data);
                }
                fclose($handle);
                exit;
    }


        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Category::where('status','!=',2)->orderAsc()
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%');
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Category::where('status','!=',2)->orderAsc()->paginate($page_limit);
        }
        $d['fetchdata'] = $fetch;
        return view('admin.category.categories',$d);
    }

    public function save_category(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);
        $input = $request->all();

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/categories/'), $imageName);
            $input['image'] = $imageName;
        }else{
            $input['image'] = 'default.png';
        }
        $create = Category::create($input);
        if($create->id){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Category not saved! Something went wrong! Please try again!');
    }

    public function edit_category(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/categories/'), $imageName);
            $input['image'] = $imageName;
        }
        unset($input['_token']);
        $create = Category::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Category not saved! Something went wrong! Please try again!');

    }

    public function load_category_data($id)
    {
        $data['category']= Category::where('id',$id)->first();
        return View::make('admin.category.category-form',$data)->render();
    }

    public function delete_category($id)
    {
        Category::where('id',$id)->update(['status'=>2]);
        return redirect()->back()->with('warning','Deleted successfully!');
    }



    public function fetch_subcategories(Request $request)
    {

        $d['page_title'] = 'Subcategories';
        $d['categories'] = Category::active()->get();
        $page_limit = 10;



  if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("id","Name","Category","Image","Status","Position","Added");

            if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Subcategory::where('status','!=',2)
                    ->with('Category')
                    ->orderAsc()
                    ->where(function($query)  use ($q) {
                        $query->where('name','LIKE', '%' . $q . '%')
                        ->orWhereHas('Category', function($query)  use ($q){
                            $query->where('name', $q);
                        });
                    })
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Subcategory::where('status','!=',2)->orderAsc()->paginate();
                }

           
                $i = 1;
                foreach ($fetch as $user) {
                    $st="";
                       if ($user->status == 1)
                       {
                        $st ="Active";
                        }
                         else
                       {
                            if ($user->status == 2) {
                                $st = "Deleted";
                            }
                            else{
                                $st = "Disable";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "id"=>$user->id,
                   "name"=>$user->name,
                   "category"=>$user->category ? $user->category->name : '',
                   "image_url"=>$user->image_url,
                   "status"=>$st,
                   "position"=>$user->position,              
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_subcategories" . $string_file . ".csv");
                header("Pragma: no-cache");
                header("Expires: 0");

                $handle = fopen('php://output', 'w');

                foreach ($data as $data) {
                    fputcsv($handle, $data);
                }
                fclose($handle);
                exit;
    }


        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Subcategory::where('status','!=',2)
            ->with('Category')
            ->orderAsc()
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhereHas('Category', function($query)  use ($q){
                    $query->where('name', $q);
                });
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Subcategory::where('status','!=',2)->orderAsc()->paginate($page_limit);
        }

        $d['fetchdata'] = $fetch;
        return view('admin.category.subcategories',$d);
    }

    public function save_subcategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);
        $input = $request->all();

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/subcategories/'), $imageName);
            $input['image'] = $imageName;
        }else{
            $input['image'] = 'default.png';
        }
        $create = Subcategory::create($input);
        if($create->id){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Category not saved! Something went wrong! Please try again!');
    }

    public function edit_subcategory(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'category_id'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
        ]);

        $input = $request->all();

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/subcategories/'), $imageName);
            $input['image'] = $imageName;
        }
        unset($input['_token']);
        $create = Subcategory::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Category not saved! Something went wrong! Please try again!');

    }

    public function load_subcategory_data($id)
    {
        $data['category']= Subcategory::where('id',$id)->first();
        $data['categories'] = Category::active()->get();
        return View::make('admin.category.subcategory-form',$data)->render();
    }

    public function subcategories_by_category_dropdown_html($id)
    {
        $html='<option value="">Choose...</option>';
        $subcat= Subcategory::where('category_id',$id)->get();
        if($subcat){
            foreach($subcat as $s){
                $html.='<option value="'.$s->id.'">'.$s->name.'</option>';
            }
        }
        return $html;

    }

    public function delete_subcategory($id)
    {
        Subcategory::where('id',$id)->update(['status'=>2]);
        return redirect()->back()->with('warning','Deleted successfully!');
    }

}

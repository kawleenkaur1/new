<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\UNIT;
use App\Models\Product;
use App\Models\ProductConnection;
use App\Models\Location;
use App\Models\ProductPrice;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function fetch_products()
    {
        $page_limit =100;
        $template['page_title'] = 'Products';

        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("ProductID","Img","Name","Category","Subcategory","Position","Qty","Selling","Stock","Status","Added");

                 if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Product::where('status','!=',2)->orderAsc()
                    ->with('Category')
                    ->with('Subcategory')

                    ->where(function($query)  use ($q) {
                        $query->where('name','LIKE', '%' . $q . '%')
                        ->orWhere('id','LIKE', '%' . $q . '%')
                        ->orWhereHas('Category', function($query)  use ($q){
                            $query->where('name', $q);
                        })
                        ->orWhereHas('Subcategory', function($query)  use ($q){
                            $query->where('name', $q);
                        });
                    })
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Product::where('status','!=',2)->orderAsc()->paginate(500000);
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
                                $st = "Suspended";
                            }
                            else{
                                $st = "Inactive";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "id"=>$user->id,
                   "image_url"=>$user->image_url,
                   "name"=>$user->name,
                   "category"=>$user->category?$user->category->name:'',
                   "subcategory"=>$user->subcategory?$user->subcategory->name:'',
                   "position"=>$user->position,              
                   "unit"=>$user->unit,              
                   "selling_price"=>$user->selling_price, 
                   "stock"=>$user->stock,              

                   "status"=>$st,
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_products" . $string_file . ".csv");
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
            $fetch = Product::where('status','!=',2)->orderAsc()
            ->with('Category')
            ->with('Subcategory')

            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('id','LIKE', '%' . $q . '%')
                ->orWhereHas('Category', function($query)  use ($q){
                    $query->where('name', $q);
                })
                ->orWhereHas('Subcategory', function($query)  use ($q){
                    $query->where('name', $q);
                });
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Product::where('status','!=',2)->orderAsc()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.product.products',$template);
    }

    public function fetch_deal_products()
    {
        $page_limit =100;
        $template['page_title'] = 'Deal of the day Products';
        $template['deal_product'] = true;

        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("ProductID","Img","Name","Category","Subcategory","Position","Qty","Selling","Stock","Status","Added");

                 if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Product::where('status','!=',2)->deal()->orderAsc()
                    ->with('Category')
                    ->with('Subcategory')

                    ->where(function($query)  use ($q) {
                        $query->where('name','LIKE', '%' . $q . '%')
                        ->orWhere('id','LIKE', '%' . $q . '%')
                        ->orWhereHas('Category', function($query)  use ($q){
                            $query->where('name', $q);
                        })
                        ->orWhereHas('Subcategory', function($query)  use ($q){
                            $query->where('name', $q);
                        });
                    })
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Product::where('status','!=',2)->deal()->orderAsc()->paginate(500000);
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
                                $st = "Suspended";
                            }
                            else{
                                $st = "Inactive";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "id"=>$user->id,
                   "image_url"=>$user->image_url,
                   "name"=>$user->name,
                   "category"=>$user->category?$user->category->name:'',
                   "subcategory"=>$user->subcategory?$user->subcategory->name:'',
                   "position"=>$user->position,              
                   "unit"=>$user->unit,              
                   "selling_price"=>$user->selling_price, 
                   "stock"=>$user->stock,              

                   "status"=>$st,
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_products" . $string_file . ".csv");
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
            $fetch = Product::where('status','!=',2)->deal()->orderAsc()
            ->with('Category')
            ->with('Subcategory')

            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('id','LIKE', '%' . $q . '%')
                ->orWhereHas('Category', function($query)  use ($q){
                    $query->where('name', $q);
                })
                ->orWhereHas('Subcategory', function($query)  use ($q){
                    $query->where('name', $q);
                });
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Product::where('status','!=',2)->deal()->orderAsc()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.product.products',$template);
    }

    public function delete_product($id)
    {
        Product::where('id',$id)->update(['status'=>2]);
        return redirect()->back()->with('warning','Deleted successfully!');
    }

    public function create_product()
    {
        $template['page_title'] = 'Create Product';
        $template['subcategories'] = [];
        $template['categories'] = Category::active()->orderBy('name','asc')->get();
        $template['unit'] = UNIT::active()->orderBy('name','asc')->get();
        $template['cities'] = Location::active()->orderBy('name','asc')->get();

        $breadcrumb = [
            0=>[ 'title'=>'Products',
             'link'=>route('fetch_products') ]
         ];
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.product.create_product',$template);
    }
    public function save_product(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'selling_price'=>'required|numeric',
            // 'discount'=>'numeric|max:100',
            // // 'mrp'=>'required',
            // 'subscription_price'=>'required|numeric',
            'status'=>'required',
            // 'mark_as_new'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg,webp|max:15000',

        ]);
        $input = $request->all();
        $category_insert=[];
        $category_id=$input['category_id'];
        $input['category_id'] = intval($input['category_id'][0]);
        $input['subcategory_id']=0;

        $un = UNIT::active()->where('id',$input['unit'])->first();
        $input['unit'] =  $un ? $un->name :'';

        // $input['discount'] = $discount = intval($input['discount']);
        // $input['selling_price']=$price = floatval($input['selling_price']);
        // $input['mrp'] = $price;
        // if(!empty($discount)){
        //     $discount_price = round($price*($discount/100));
        //     $selling_price = $price - $discount_price;
        //     $input['selling_price'] = $selling_price <0 ? 0 : $selling_price;
        // }

        $input['category_ids_string'] =implode(',',$category_id);
        $input['hifen_name'] ="";
        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/products/'), $imageName);
            $input['image'] = $imageName;
        }else{
            unset($input['image']);
        }
        if($_FILES['hover_image']['size']>0){
            $hover_imageName = time().'.'.$request->hover_image->extension();
            $request->hover_image->move(public_path('uploads/products/'), $hover_imageName);
            $input['hover_image'] = $hover_imageName;
        }else{
            unset($input['hover_image']);
        }

        if(isset($input['deal_product'])){
            //$validity=explode('to',$input['start_end']);
            $input['is_deal'] = 1;
            $input['start_date'] = date('Y-m-d H:i:s',strtotime($input['start_date'].' '.$input['start_time']));
            $input['end_date'] = date('Y-m-d H:i:s',strtotime($input['end_date'].' '.$input['end_time']));
            /**if(count($validity)>1){
                /** date('Y-m-d H:i:s',strtotime($input['start_date'].' '.$input['starr_time'])) 
                $input['start_date'] =  date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime(trim($validity[0])))." 00:00:00"));
                $input['end_date'] = date("Y-m-d H:i:s",strtotime(date('Y-m-d',strtotime(trim($validity[1])))." 24:00:00"));
            }elseif(count($validity) == 1){
                $input['start_date'] = date("Y-m-d H:i:s",strtotime(date('Y-m-d',strtotime(trim($validity[0])))." 00:00:00"));
                $input['end_date'] = date("Y-m-d H:i:s",strtotime(date('Y-m-d',strtotime(trim($validity[0])))." 24:00:00"));
            }*/
            unset($input['start_end']);
        }
        // unset($input['deal_product']);


        $gallery=array();
        if($files=$request->file('gallery')){
            foreach($files as $file){
                $name= time().'.'.$file->getClientOriginalName();
                $file->move(public_path('uploads/products/'),$name);
                $gallery[]=$name;
            }
        }

       $input['gallery'] =  implode("|", $gallery);

// print_r( $input['gallery'] ); die;
        $price_mng = $input['price_mng'];
        unset($input['price_mng']);
        // echo "<pre>";
        // print_r($input);
        // die;
        $create = Product::create($input);
        if($create->id){
            $create->code='SKU'.$create->id;
            $create->hifen_name=implode('-',explode(' ',$input['name'])).'-'.$create->code;
            $create->save();
            if(!empty($category_id)){
                $date=date('Y-m-d H:i:s');
                foreach ($category_id as $c ) {
                    $category_insert=[
                        'category_id'=>$c,
                        'product_id'=>$create->id,
                        'created_at'=>$date,
                        'updated_at'=>$date
                    ];
                    ProductConnection::insert($category_insert);
                }
            }

            $locations = $price_mng['location_id'];
            $selling_prices = $price_mng['selling_price'];
            $discounts = $price_mng['discount'];
            $lengthcout = count($locations);
            $product_id = $create->id;
            for ($i=0; $i < $lengthcout; $i++) { 
                $prod_price_arr = [];
                $lc_id = $locations[$i];
                $sp = floatval($selling_prices[$i]);
                $dis = intval($discounts[$i]);
                $slprice = 0;
                if($dis > 100){
                    $dis=0;
                }
                if($sp<0){
                    $sp=0;
                }
                if(!empty($dis)){
                    $discount_price = ($sp*($dis/100));
                    $selling_price = $sp - $discount_price;
                    $slprice = $selling_price <0 ? 0 : $selling_price;
                }else{
                    $slprice = $sp;
                }
                $prod_price_arr['location_id'] = $lc_id;
                $prod_price_arr['selling_price'] = $slprice;
                $prod_price_arr['mrp'] = $sp;
                $prod_price_arr['discount'] = $dis;
                $prod_price_arr['discount_type']=1;
                $prod_price_arr['product_id']=$product_id;
                $prod_price_arr['created_at'] = $date;
                $prod_price_arr['updated_at'] = $date;

                $checkp = ProductPrice::where('product_id',$product_id)->where('location_id',$lc_id)->first();
                if($checkp){
                    ProductPrice::where('id',$checkp->id)->update($prod_price_arr);
                }else{
                    ProductPrice::create($prod_price_arr);
                }
            }
            if(isset($input['deal_product'])){
                return Redirect::route('fetch_deal_products')->with('success', 'Successfully Saved! With Unique ID-#'.$create->id);
            }else{
            return Redirect::route('fetch_products')->with('success', 'Successfully Saved! With Unique ID-#'.$create->id);}

        }
        return redirect()->back()->with('danger', 'Product not saved! Something went wrong! Please try again!');
    }

    public function create_deal_product()
    {
        $template['page_title'] = 'Create Deal of the Day Product';
        $template['subcategories'] = [];
        $template['categories'] = Category::active()->orderBy('name','asc')->get();
        $template['unit'] = UNIT::active()->orderBy('name','asc')->get();
        $template['cities'] = Location::active()->orderBy('name','asc')->get();

        $template['deal_product'] = true;
        $breadcrumb = [
            0=>[ 'title'=>'Products',
             'link'=>route('fetch_products') ]
         ];
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.product.create_product',$template);
    }

    public function edit_product($id)
    {
        $template['page_title'] = 'Edit Product';
        $template['categories'] = Category::active()->orderBy('name','asc')->get();
        $template['location'] = Location::active()->orderBy('name','asc')->get();
        $template['unit'] = UNIT::active()->orderBy('name','asc')->get();
        $template['category'] = $product = Product::where('id',$id)->where('status','!=',2)->first();
// print_r( $template['category'] ); die;
        $template['subcategories'] = Subcategory::where('category_id',$product->category_id)->orderBy('name','asc')->get();
        $template['add_categories'] = ProductConnection::where('product_id',$id)->orderBy('product_id','asc')->get();
        if(!$product){
            return Redirect::route('fetch_products')->with('danger','Product not found');
        }
        $template['cities'] = Location::active()->orderBy('name','asc')->get();
        $template['productprices'] = ProductPrice::where('product_id',$id)->get();
        $breadcrumb = [
            0=>[ 'title'=>'Products',
             'link'=>route('fetch_products') ]
         ];
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.product.create_product',$template);
    }




    public function update_product(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            // 'selling_price'=>'required',
            // 'discount'=>'numeric|max:100',
            // 'subscription_price'=>'required',
            'status'=>'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:15000',

        ]);
        $input = $request->all();
        $product = Product::where('id',$id)->whereIn('status',[0,1])->first();
        if(!$product){
            redirect()->back()->with('danger', 'Product not found!');
        }


        $input['subcategory_id'] = 0;
        $category_insert=[];
        $category_id=$input['category_id'];
        $input['category_id'] = intval($input['category_id'][0]);

        $un = UNIT::active()->where('id',$input['unit'])->first();
        $input['unit'] =  $un ? $un->name :'';


        // $input['discount'] = $discount = intval($input['discount']);
        // $input['selling_price']=$price = floatval($input['selling_price']);
        // $input['mrp'] = $price;
        // if(!empty($discount)){
        //     $discount_price = round($price*($discount/100));
        //     $selling_price = $price - $discount_price;
        //     $input['selling_price'] = $selling_price <0 ? 0 : $selling_price;
        // }

        if($_FILES['image']['size']>0){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/products/'), $imageName);
            $input['image'] = $imageName;
        }
        if($_FILES['hover_image']['size']>0){
            $hover_imageName = time().'.'.$request->hover_image->extension();
            $request->hover_image->move(public_path('uploads/products/'), $hover_imageName);
            $input['hover_image'] = $hover_imageName;
        }

        if(isset($input['deal_product'])){
            $validity=explode('to',$input['start_end']);
            $input['is_deal'] = 1;
            if(count($validity)>1){
                $input['start_date'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime(trim($validity[0])))." 00:00:00"));
                $input['end_date'] = date("Y-m-d H:i:s",strtotime(date('Y-m-d',strtotime(trim($validity[1])))." 24:00:00"));
            }elseif(count($validity) == 1){
                $input['start_date'] = date("Y-m-d H:i:s",strtotime(date('Y-m-d',strtotime(trim($validity[0])))." 00:00:00"));
                $input['end_date'] = date("Y-m-d H:i:s",strtotime(date('Y-m-d',strtotime(trim($validity[0])))." 24:00:00"));
            }
            unset($input['start_end']);
        }

        unset($input['deal_product']);

          $gallery=array();
        if($files=$request->file('gallery')){
            foreach($files as $file){
                $name= time().'.'.$file->getClientOriginalName();
                $file->move(public_path('uploads/products/'),$name);
                $gallery[]=$name;
            }
        }

        $input['gallery'] =  implode("|", $gallery);
        // print_r( $input['gallery']); die;
        unset($input['_token']);
        unset($input['Save']);

        // $input['hifen_name'] =implode("-",explode(' ',$input['name']));
        // $input['hifen_name'] ="";
        $input['code']=$code='SKU'.$id;
        $input['hifen_name']=implode('-',explode(' ',$input['name'])).'-'.$code;
        $input['category_ids_string'] =implode(',',$category_id);


        $price_mng = $input['price_mng'];
        unset($input['price_mng']);

        $create = Product::where('id',$id)->update($input);
        ProductConnection::where('product_id',$id)->delete();
        // ProductPrice::where('product_id',$id)->delete();
        $date=date('Y-m-d H:i:s');
        if($create){
            if(!empty($category_id)){

                foreach ($category_id as $c ) {
                    $category_insert=[
                        'category_id'=>$c,
                        'product_id'=>$id,
                        'created_at'=>$date,
                        'updated_at'=>$date
                    ];
                    ProductConnection::insert($category_insert);
                }
            }
            $locations = $price_mng['location_id'];
            $selling_prices = $price_mng['selling_price'];
            $discounts = $price_mng['discount'];
            $lengthcout = count($locations);
            $product_id = $id;
            for ($i=0; $i < $lengthcout; $i++) { 
                $prod_price_arr = [];
                $lc_id = intval($locations[$i]);
                $sp = floatval($selling_prices[$i]);
                $dis = intval($discounts[$i]);
                $slprice = 0;
                if($dis > 100){
                    $dis=0;
                }
                if($sp<0){
                    $sp=0;
                }
                if(!empty($dis)){
                    $discount_price = ($sp*($dis/100));
                    $selling_price = $sp - $discount_price;
                    $slprice = $selling_price <0 ? 0 : $selling_price;
                }else{
                    $slprice = $sp;
                }
                $prod_price_arr['location_id'] = $lc_id;
                $prod_price_arr['selling_price'] = $slprice;
                $prod_price_arr['mrp'] = $sp;
                $prod_price_arr['discount'] = $dis;
                $prod_price_arr['discount_type']=1;
                $prod_price_arr['product_id']=$product_id;
                $prod_price_arr['created_at'] = $date;
                $prod_price_arr['updated_at'] = $date;

                $checkp = ProductPrice::where('product_id',$product_id)->where('location_id',$lc_id)->first();
                if($checkp){
                    ProductPrice::where('id',$checkp->id)->update($prod_price_arr);
                }else{
                    ProductPrice::create($prod_price_arr);
                }
            }
            // return Redirect::route('fetch_products')->with('success', 'Successfully Saved Product of unique ID-#'.$id);
            if(isset($input['deal_product'])){
                return Redirect::route('fetch_deal_products')->with('success', 'Successfully Saved! With Unique ID-#'.$id);
            }else{
            return Redirect::route('fetch_products')->with('success', 'Successfully Saved! With Unique ID-#'.$id);}

        }
        return redirect()->back()->with('danger', 'Product not saved! Something went wrong! Please try again!');
    }



}

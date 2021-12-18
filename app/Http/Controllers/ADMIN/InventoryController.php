<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class InventoryController extends Controller
{
    //

    public function fetch_outofstocks(Request $request)
    {
        $page_limit =10;
        $template['page_title'] = 'Products Stock less than 10';

        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Product::where('status','!=',2)->orderBy('id','desc')
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('selling_price','LIKE', '%' . $q . '%');
            })
            ->where('stock','<=',10)
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Product::where('status','!=',2)->orderBy('id','desc')
            ->where('stock','<=',10)
            ->paginate($page_limit);
        }
        $template['reset_link']=route('fetch_outofstocks');
        $template['fetchdata'] = $fetch;
        return view('admin.product.products',$template);
    }

    public function fetch_inventoryproducts(Request $request)
    {
        $page_limit =100;
        $template['page_title'] = 'Select Inventory Product';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Product::where('status','!=',2)->orderAsc()
            ->with('Category')
            // ->with('Subcategory')

            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('id','LIKE', '%' . $q . '%')
                ->orWhereHas('Category', function($query)  use ($q){
                    $query->where('name', $q);
                });
                // ->orWhereHas('Subcategory', function($query)  use ($q){
                //     $query->where('name', $q);
                // });
            })
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Product::where('status','!=',2)->orderAsc()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.inventory.products',$template);
    }

    public function fetch_inventory_by_warehouse($id)
    {
        $user=User::where('id',$id)->first();
        if(!$user){
            return redirect()->back()->with('danger', 'Warehouse not saved! Something went wrong! Please try again!');
        }
        $page_limit =100;
        $breadcrumb = [
            0=>[ 'title'=>'Warehouse',
             'link'=>route('fetch_warehouses') ]
         ];
         $template['breadcrumb']=$breadcrumb;
        $template['page_title'] = $user->name.' | Inventory Records';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Inventory::
            with('Product')
            ->where(function($query)  use ($q) {
                $query->where('product_id','LIKE', '%' . $q . '%')
                ->orWhere('user_id','LIKE', '%' . $q . '%')
                ->orWhereHas('Product', function($query)  use ($q){
                    $query->where('name', $q);
                });
            })
            ->where('user_id',$user->id)
            ->orderBy('updated_at','desc')
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Inventory::orderBy('updated_at','desc')->where('user_id',$user->id)->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.inventory.index',$template);
    }

    public function fetch_all_inventory(Request $request)
    {
        $page_limit =100;
        $template['page_title'] = 'Inventory Records';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Inventory::
            with('Product')
            ->where(function($query)  use ($q) {
                $query->where('product_id','LIKE', '%' . $q . '%')
                ->orWhere('user_id','LIKE', '%' . $q . '%')
                ->orWhereHas('Product', function($query)  use ($q){
                    $query->where('name', $q);
                });
            })
            ->orderBy('id','desc')
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Inventory::orderBy('id','desc')->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.inventory.index',$template);
    }
    public function fetch_inventory(Request $request)
    {
        $page_limit =100;
        $template['page_title'] = 'Inventory Records';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Inventory::
            with('Product')
            ->where(function($query)  use ($q) {
                $query->where('product_id','LIKE', '%' . $q . '%')
                ->orWhere('user_id','LIKE', '%' . $q . '%')
                ->orWhereHas('Product', function($query)  use ($q){
                    $query->where('name', $q);
                });
            })
            ->orderBy('id','desc')
            ->inStock()
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Inventory::orderBy('id','desc')->inStock()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.inventory.index',$template);
    }

    public function fetch_inventoryout(Request $request)
    {
        $page_limit =100;
        $template['page_title'] = 'Inventory Records';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Inventory::
            with('Product')
            ->where(function($query)  use ($q) {
                $query->where('product_id','LIKE', '%' . $q . '%')
                ->orWhere('user_id','LIKE', '%' . $q . '%')
                ->orWhereHas('Product', function($query)  use ($q){
                    $query->where('name', $q);
                });
            })
            ->orderBy('updated_at','desc')
            ->outStock()
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Inventory::orderBy('updated_at','desc')->outStock()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('admin.inventory.index',$template);
    }

    public function manage_inventory($id)
    {
        $product=Product::where('id',$id)->first();
        $template['page_title'] = 'Add Inventory Product:'.$product->name.'| Product ID: '.$id;
        $template['product']=$product;
        $breadcrumb = [
            0=>[ 'title'=>'Inventory Products',
             'link'=>route('fetch_inventoryproducts') ]
         ];
         $template['breadcrumb']=$breadcrumb;
        $template['warehouses']=User::warehouse()->get();
        return view('admin.inventory.addedit',$template);
    }

    public function count_total_stock($product_id,$user_id,$stock)
    {
        $warehouse = User::where('id',$user_id)->first();
    
        $create_ids = [];
        if($warehouse){
            $pincodes_arr = explode('|',$warehouse->pincode_allowance);
            $locations = Location::whereIn('pincode',$pincodes_arr)->get();
            foreach ($locations as $lc) {
                $productprice = ProductPrice::where('product_id',$product_id)->where('location_id',$lc->id)->first();
                if($productprice){
                    $productprice->stock = intval($productprice->stock) + intval($stock);
                    $productprice->save();
                }
                // else{
                //     $locations = Location::whereIn('id',$lc->id)->first();
                // }
            }
        }

        return true;

        
    }

    public function save_inventory(Request $request)
    {
        $request->validate([
            'stock' => 'required',
        ]);
        $input = $request->all();
        $a['product_id']=$input['product_id'];
        $a['user_id']=$input['user_id'];
        $a['stock']=$input['stock'];
        $a['stock_status']=1;
        $a['added_by']=Auth::user()->id;
        $a['comment']=$input['comment'];
        $product=Product::where('id',$input['product_id'])->first();
    

        $warehouse = User::where('id',$input['user_id'])->first();

        $location = Location::where('lat',$warehouse->latitude)->where('lon',$warehouse->longitude)->first();
        if($location){
            $productprice = ProductPrice::where('product_id',$product->id)->where('location_id',$location->id)->first();
            if(!$productprice){
                return redirect()->back()->with('danger', 'Oops! Stock not added, Please add the '.$location->name.' location price first for this product then add stock of this product for the location '.$location->name.'! ');  
            }
        }

        $create = Inventory::create($a);

       
        $this->count_total_stock($product->id,$input['user_id'],$input['stock']);

        if($create->id){
            return Redirect::route('fetch_inventory')->with('success', 'Successfully Saved! With Product Unique ID-#'.$input['product_id']);

        }
        return redirect()->back()->with('danger', 'Product not saved! Something went wrong! Please try again!');
    }

    public function add_to_stock($id)
    {
        $inventory=Inventory::where('id',$id)->first();
        if(!$inventory){
            return redirect()->back()->with('danger', 'Inventory Not found!');
        }
        if($inventory->status!=1){
            return redirect()->back()->with('danger', 'Inventory Not found!');
        }
        $product=Product::where('id',$inventory->product_id)->first();
        $old_stock=$product->stock;
        $update_stock=$old_stock+$inventory->stock;
        $product->stock=$update_stock;
        $product->save();
        $inventory->status=2;
        $inventory->stock_status=2;

        $inventory->save();
        
        return redirect()->back()->with('success', 'Stock '.$inventory->stock.' added Successfully to With Product : '.$product->name.' Unique ID-#'.$inventory->product_id);
        // $new=[
        //     'product_id'=>$inventory->product_id,
        //     'user_id'=>$inventory->user_id,
        //     'stock'=>$inventory->stock,
        //     'stock_status'=>2,
        //     'status'=>2,
        //     'added_by'=>Auth::user()->id
        // ];
        // $create=Inventory::create()
    }
}

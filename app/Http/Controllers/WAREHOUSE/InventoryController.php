<?php

namespace App\Http\Controllers\WAREHOUSE;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
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

    public function fetch_inventoryproductswarehouse(Request $request)
    {
        $page_limit =10;
        $template['page_title'] = 'Select Inventory Product';
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
        return view('warehouse.inventory.products',$template);
    }

    public function fetch_inventory_by_warehouse($id)
    {
        $user=User::where('id',$id)->first();
        if(!$user){
            return redirect()->back()->with('danger', 'Warehouse not saved! Something went wrong! Please try again!');
        }
        $page_limit =10;
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

    public function fetch_all_inventorywarehouse(Request $request)
    {
          $user = Auth::user();
        $id = $user->id;
        $page_limit =10;
        $template['page_title'] = 'Inventory Records';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Inventory::where('user_id',$id)->
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
            $fetch = Inventory::where('user_id',$id)->orderBy('id','desc')->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('warehouse.inventory.index',$template);
    }
    public function fetch_inventorywarehouse(Request $request)
    {
         $user = Auth::user();
        $id = $user->id;
        $page_limit =10;
        $template['page_title'] = 'Inventory Records';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Inventory::where('user_id',$id)->
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
            $fetch = Inventory::where('user_id',$id)->orderBy('id','desc')->inStock()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('warehouse.inventory.index',$template);
    }

    public function fetch_inventoryoutwarehouse(Request $request)
    {
         $user = Auth::user();
        $id = $user->id;
        $page_limit =10;
        $template['page_title'] = 'Inventory Records';
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Inventory::where('user_id',$id)->
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
            $fetch = Inventory::where('user_id',$id)->orderBy('updated_at','desc')->outStock()->paginate($page_limit);
        }
        $template['fetchdata'] = $fetch;
        return view('warehouse.inventory.index',$template);
    }

    public function manage_inventorywarehouse($id)
    {
        $product=Product::where('id',$id)->first();
        $template['page_title'] = 'Add Inventory Product:'.$product->name.'| Product ID: '.$id;
        $template['product']=$product;
        $breadcrumb = [
            0=>[ 'title'=>'Inventory Products',
             'link'=>route('fetch_inventoryproducts') ]
         ];
         $template['breadcrumb']=$breadcrumb;
          $user = Auth::user();
          $template['user_id']= $user->id;

        $template['warehouses']=User::warehouse()->get();
        return view('warehouse.inventory.addedit',$template);
    }

    public function save_inventorywarehouse(Request $request)
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
        $create = Inventory::create($a);

        $product=Product::where('id',$input['product_id'])->first();
        $old_stock=$product->stock;
        $update_stock=$old_stock+$input['stock'];
        $product->stock=$update_stock;
        $product->save();

        if($create->id){
            return Redirect::route('fetch_inventorywarehouse')->with('success', 'Successfully Saved! With Product Unique ID-#'.$input['product_id']);

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

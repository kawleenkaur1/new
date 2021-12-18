<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            {{-- <form method="POST" action="{{isset($user) ? route('update_inventory',['id'=>$user->id]) : route('save_inventory')}}" enctype="multipart/form-data"> --}}
            <form method="POST" action="{{route('save_inventorywarehouse')}}" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="name" name="name" value="{{ isset($product) ? $product->name : old('name') }}" class="form-control"  placeholder="Name" readonly required>
                </div>
                <input type="hidden" name="product_id" value="{{$product->id}}">
                {{-- <div class="form-group" >
                    <label class="control-label" for="">Qty</label>
                    <input type="number" value="{{ isset($product) ? $product->qty : old('qty') }}" min="0" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" name="qty" class="form-control" placeholder="Enter QTY" readonly required>
                </div> --}}

                <div class="form-group">
                    <label for="name">Measurement</label>
                    <input type="text" name="unit" value="{{ $product->net_wt }} {{ $product->unit }}" class="form-control"  placeholder="example:Kg.." readonly required>
                </div>

         
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" class="form-control" >


                <div class="form-group" >
                    <label class="control-label" for="">Stock</label>
                    <input type="number" value="{{ isset($category) ? $category->stock : old('stock') }}" min="0" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" name="stock" class="form-control" placeholder="Enter Stock" required>
                </div>
                @csrf

                <div class="form-group">
                    <label for="">Any Comment</label>
                    <textarea name="comment" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>



<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            {{-- <form method="POST" action="{{isset($user) ? route('update_inventory',['id'=>$user->id]) : route('save_inventory')}}" enctype="multipart/form-data"> --}}
            <form method="POST" action="{{route('save_inventory')}}" enctype="multipart/form-data">

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

                <div class="form-group">
                    <label for="">Warehouse</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Select warehouse..</option>
                        @if (!empty($warehouses))
                            @foreach ($warehouses as $c)
                                @if (isset($inventory))
                                    <?php $selected = $c->id == $inventory->user_id ? 'selected':''; ?>
                                @else
                                <?php $selected =  ''; ?>
                                @endif
                                <option value="{{$c->id}}" {{ $selected }}>{{$c->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

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



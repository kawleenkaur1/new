<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            {{-- {{isset($coupon) ? route('update_product',['id'=>$coupon->id]) : route('save_product')}} --}}
            <form method="POST" action="{{isset($coupon) ? route('update_coupon',['id'=>$coupon->id]) : route('save_coupon')}}" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" name="name" value="{{ isset($coupon) ? $coupon->name : old('name') }}" class="form-control"  placeholder="Name" required>
                    </div>

                    <div class="form-group">
                        <label for="">Coupon Type</label>
                        <select  name="type" id="coupon_type" class="form-control" required>
                            <option value="">Choose...</option>
                            @if (isset($coupon))
                                <option value="1" {{$coupon->type == 1 ? 'selected' : ''}}>Percentage</option>
                                <option value="2" {{$coupon->type == 2 ? 'selected' : ''}}>Flat</option>
                            @else
                                <option value="1" >Percentage</option>
                                <option value="2" >Flat</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group" >
                        <label class="control-label" for="">Discount</label>
                        <input type="number" id="discount" value="{{ isset($coupon) ? $coupon->discount : old('discount') }}" min="0" name="discount" class="form-control" placeholder="Enter Discount">
                    </div>

                    <div class="form-group" id="min_order_amount_div" style="display: none">
                        <label for="">Minimum Order Amount</label>
                        <input type="number" id="min_order_amount" min="0" step="0.01" value="{{ isset($coupon) ? $coupon->min_order_amount : old('min_order_amount') }}"  name="min_order_amount" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" class="form-control" placeholder="Enter Minimum Order Amount" >
                    </div>

                    <div class="form-group" id="max_discount_div" style="display: none">
                        <label class="control-label" for="">Maximum Discount</label>
                        <input type="number" id="max_discount" min="0" step="0.01" value="{{ isset($coupon) ? $coupon->max_discount : old('max_discount') }}"  name="max_discount" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" class="form-control" placeholder="Enter Maximum Discount" >
                    </div>

                    <div class="form-group" >
                        <label class="control-label" for="">Use Limit per user</label>
                        <input type="number" id="use_limit" min="0"  value="{{ isset($coupon) ? $coupon->use_limit : old('use_limit') }}"  name="use_limit" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" class="form-control" placeholder="Use Limit Per User" required>
                    </div>
                    <div class="form-group" >
                        <label class="control-label" for="">Coupon Validity</label>
                        <input id="rangeCalendarFlatpickr" value="{{ isset($coupon) ? $coupon->starts_on.' to '.$coupon->expires_on : old('validity') }}" name="validity" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date.." required>
                    </div>


                    <div class="form-group" >
                        <label class="control-label" for="">Position</label>
                        <input type="number" value="{{ isset($coupon) ? $coupon->position : old('position') }}" min="0" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" name="position" class="form-control" placeholder="Position" >
                    </div>

                    @csrf
                    <div class="form-group">
                        <label for="inputState">Status</label>
                        <select id="inputState" name="status" class="form-control" required>
                            <option value="">Choose...</option>
                            @if (isset($coupon))
                                <option value="1" {{$coupon->status == 1 ? 'selected' : ''}}>Active</option>
                                <option value="0" {{$coupon->status == 0 ? 'selected' : ''}}>Disable</option>
                            @else
                                <option value="1" >Active</option>
                                <option value="0" >Disable</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group" >
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
            </form>
        </div>
    </div>
</div>


@section('scripts')
    <script>
        var ctype= $('#coupon_type').val();
        if(ctype == 1){
                $('#max_discount_div').show();
                $('#min_order_amount_div').hide();
            }else if(ctype == 2){
                $('#max_discount_div').hide();
                $('#min_order_amount_div').show();
            }
        $('#coupon_type').on('change', function() {
            const type = $(this).val();
            if(type == 1){
                $('#max_discount_div').show();
                $('#min_order_amount_div').hide();
            }else if(type == 2){
                $('#max_discount_div').hide();
                $('#min_order_amount_div').show();
            }

        });
    </script>
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            {{-- {{isset($category) ? route('update_product',['id'=>$category->id]) : route('save_product')}} --}}
            <form method="POST" action="{{isset($category) ? route('update_product',['id'=>$category->id]) : route('save_product')}}" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="name" name="name" value="{{ isset($category) ? $category->name : old('name') }}" class="form-control"  placeholder="Name" required>
                        </div>
        
                        <div class="form-group">
                            <label for="inputState">Category</label>
                            <select  name="category_id[]"  class="form-control tagging" multiple="multiple" >
                                @if (!empty($categories))

                                    @foreach ($categories as $it)

                                        @if (isset($category))
                                    <?php $category_ids_staring = explode(',',$category->category_ids_string); ?>

                                            <?php $selected_owner = in_array( $it->id,$category_ids_staring) ? 'selected' : ''; ?>
                                        @else
                                            <?php $selected_owner =''; ?>
                                        @endif

                                        @if (old('category_id'))
                                            <?php $selected_owner = old('category_id') == $it->id ? 'selected' : 'selected'; ?>
                                        @endif
                                        <option value="{{$it->id}}" {{$selected_owner}}>{{$it->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label for="inputState">Subcategory</label>
                            <select  name="subcategory_id" id="subcategory" class="form-control" >
                                <option value="" selected>Choose...</option>
                                @if (!empty($subcategories))

                                    @foreach ($subcategories as $it)

                                        @if (isset($category))
                                            <?php $selected_owner = $category->subcategory_id == $it->id ? 'selected' : ''; ?>
                                        @else
                                            <?php $selected_owner =''; ?>
                                        @endif

                                        @if (old('subcategory_id'))
                                            <?php //$selected_owner = old('subcategory_id') == $it->id ? 'selected' : ''; ?>
                                        @endif
                                        <option value="{{$it->id}}" {{$selected_owner}}>{{$it->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>


                        <div class="form-group" >
                            <label class="control-label" for="">Weight</label>
                            <table>
                                <tr>
                                    <td>Net Wt</td>
                                    <td>Gross Wt</td>
                                    <td>Measurement</td>
                                </tr>
                                <tr>
                                    <td><input type="number" step="0.01" min="1" value="{{ isset($category) ? $category->net_wt : old('net_wt') }}" name="net_wt" class="form-control" placeholder="Net Weight" required></td>
                                    <td><input type="number" min="0" step="0.01"  value="{{ isset($category) ? $category->gross_wt : old('gross_wt') }}" name="gross_wt" class="form-control" placeholder="Gross Weight" ></td>
                                    <td>
                                        <select name="unit" class="form-control" required>
                                            <option value="">Select Unit..</option>
                                            @if (!empty($unit))
                                                @foreach ($unit as $c)
                                                    @if (isset($category))
                                                        <?php $selected = $c->name == $category->unit ? 'selected':''; ?>
                                                    @else
                                                    <?php $selected =  ''; ?>
                                                    @endif
                                                    <option value="{{$c->id}}" {{ $selected }}>{{$c->name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                    </td>
                                    
                                </tr>
                            </table>
                            
                        </div>
                    
                        @csrf
                        <div class="form-group" >
                            <label class="control-label" for="">Number of pieces</label>
                            <input type="text"  value="{{ isset($category) ? $category->no_of_pieces : old('no_of_pieces') }}" name="no_of_pieces" class="form-control" placeholder="Number of pieces" >
                        </div>
                        

                        <div class="form-group" >
                            <label class="control-label" for="">Cooking Time</label>
                            <input type="text"  value="{{ isset($category) ? $category->cooking_time : old('cooking_time') }}" name="cooking_time" class="form-control" placeholder="Cooking Time" >
                        </div>

                       
                        <div class="form-group" >
                            <label>Gallery Image</label>
                            @if (isset($category))
                                <?php $hover_img_url = $category->hover_url;
                                
                                 ?>
                            @else
                                <?php $hover_img_url = false; ?>
                            @endif
                            <input type="file" name="gallery[]"  class="form-control" accept="image/*" multiple>
                           
                        </div>

                          @if (isset($category))
                                <?php 
                                    $gallery_url = $category->gallery; 
                                    $gallery_data =   explode("|",$gallery_url);
                                    if ($gallery_data) {
                                       foreach ($gallery_data as $g_key) {
                                        ?>
                                        <img style="width:100px;" id="" src="{{$category->img_path.$g_key}}" alt="Product image"  /> 
                                        <?php
                                          // print_r($g_key); 
                                       }
                                    }
                                ?>
                            @else
                                <?php $gallery_url = false; ?>
                            @endif
                          
                          



                       

                    </div>
                    <div class="col-lg-5">
                        
                        <?php if((isset($deal_product) && $deal_product) || (isset($category) && $category->is_deal == 1)) : ?>
                        
                            <?php 
                             $start_date = '';
                            $start_time ='';
                            $end_date = '';
                            $end_time = '';
                            if(isset($category)){
                            $st_arr = explode(' ',$category->start_date);
                            if(count($st_arr)>0){
                            $start_date = $st_arr[0];
                            $start_time = $st_arr[1];
                            }
                            $st_arr1 = explode(' ',$category->start_date);
                            if(count($st_arr1)>0){
                            $end_date = $st_arr1[0];
                            $end_time = $st_arr1[1];
                            }}
                            
                            ?>
                            <div class="form-group">
                                <label for="">Start Date</label>
                                <input  type="date" id=""  value="{{$start_date}}" name="start_date" class="form-control flatpickr flatpickr-input active"  placeholder="Select Start Date.." required>
                            </div>
                            <div class="form-group">
                                <label for="">Start Time</label>
                                <input  type="time" id=""  value="{{$start_time}}" name="start_time" class="form-control flatpickr flatpickr-input active"  placeholder="Select Start Time.." required>
                            </div>
                            <div class="form-group">
                                <label for="">End Date</label>
                                <input  type="date" id=""  value="{{$end_date}}" name="end_date" class="form-control flatpickr flatpickr-input active"  placeholder="Select End Date .." required>
                            </div>
                            <div class="form-group">
                                <label for="">End Time</label>
                                <input  type="time" id=""  value="{{$end_time}}" name="end_time" class="form-control flatpickr flatpickr-input active"  placeholder="Select .." required>
                            </div>


                            <input type="hidden" name="deal_product" value="1">

                       <?php else :  ?>

                            <div class="form-group">
                                <label for="inputState">Is Combo </label>
                                <select id="inputState" name="is_combo" class="form-control" required>
                                    {{-- <option value="">Choose...</option> --}}
                                    @if (isset($category))
                                        <option value="1" {{$category->is_combo == 1 ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{$category->is_combo == 0 ? 'selected' : ''}}>No</option>
                                    @else
                                        <option value="1" {{old('is_combo') == 1 ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{old('is_combo') == 0 ? 'selected' : ''}}>No</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Mark as New</label>
                                <select  name="mark_as_new" class="form-control" required>
                                    {{-- <option value="">Choose...</option> --}}
                                    @if (isset($category))
                                        <option value="1" {{$category->mark_as_new == 1 ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{$category->mark_as_new == 0 ? 'selected' : ''}}>No</option>
                                    @else
                                        <option value="1" {{old('mark_as_new') == 1 ? 'selected' : ''}}>Yes</option>
                                        <option value="0"  {{old('mark_as_new') == 0 ? 'selected' : ''}}>No</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Mark as Hot Selling</label>
                                <select  name="mark_as_hotselling" class="form-control" required>
                                    {{-- <option value="">Choose...</option> --}}
                                    @if (isset($category))
                                        <option value="1" {{$category->mark_as_hotselling == 1 ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{$category->mark_as_hotselling == 0 ? 'selected' : ''}}>No</option>
                                    @else
                                        <option value="1" {{old('mark_as_hotselling') == 1 ? 'selected' : ''}}>Yes</option>
                                        <option value="0"  {{old('mark_as_hotselling') == 0 ? 'selected' : ''}}>No</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Mark as Best Offer</label>
                                <select  name="mark_as_bestoffers" class="form-control">
                                    {{-- <option value="0">Choose...</option> --}}
                                    @if (isset($category))
                                        <option value="1" {{$category->mark_as_bestoffers == 1 ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{$category->mark_as_bestoffers == 0 ? 'selected' : ''}}>No</option>
                                    @else
                                        <option value="1" {{old('mark_as_bestoffers') == 1 ? 'selected' : ''}} >Yes</option>
                                        <option value="0" {{old('mark_as_bestoffers') == 0 ? 'selected' : ''}}>No</option>
                                    @endif
                                </select>
                            </div>


                        <?php endif; ?>

                        

                       

                    
                           <!--  <input type="text" name="unit" value="{{ isset($category) ? $category->unit : old('unit') }}" class="form-control"  placeholder="example:Kg.." required> -->
                       
                        <div class="form-group">
                            <label for="inputState">Status</label>
                            <select id="inputState" name="status" class="form-control" required>
                                <option value="">Choose...</option>
                                @if (isset($category))
                                    <option value="1" {{$category->status == 1 ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{$category->status == 0 ? 'selected' : ''}}>Disable</option>
                                @else
                                    <option value="1" {{old('status') == 1 ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{old('status') == 0 ? 'selected' : ''}}>Disable</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group" >
                            <label>Image</label>
                            @if (isset($category))
                                <?php $img_url = $category->image_url; ?>
                            @else
                                <?php $img_url = false; ?>
                            @endif
                            <input type="file" name="image" onchange="readURL(this,'product_image');" class="form-control" accept="image/*">
                            <img style="width:100px;" id="product_image" src="{{$img_url}}" alt="Product image" style="display: {{$img_url ? 'block' : 'none'}}" />
                        </div>

                        <div class="form-group" >
                            <label>Hover Image</label>
                            @if (isset($category))
                                <?php $hover_img_url = $category->hover_url;

                                 ?>
                            @else
                                <?php $hover_img_url = false; ?>
                            @endif
                            <input type="file" name="hover_image" onchange="readURL(this,'hover_image');" class="form-control" accept="image/*">
                            <img style="width:100px;" id="hover_image" src="{{$hover_img_url}}" alt="Product Hover image" style="display: {{$hover_img_url ? 'block' : 'none'}}" />
                        </div>

                      
                       
                    </div>
                    {{-- <hr> --}}
                     {{-- <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Price Manage</label>
                            <table id="POITable" class="table table-responsive">
                                <tr>
                                    <td>Sno</td>
                                    <td>City</td>
                                    <td>Dsicount(%)</td>
                                    <td>Price</td>
                                    <td>Delete</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <select name="price_mng[location_id][]" >
                                            @if ($cities)
                                                @foreach ($cities as $ct)
                                                    <option value="{{$ct->id}}">{{$ct->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                       <input type="number" name="price_mng[discount][]" placeholder="Discount.." />
                                    </td>
                                    <td><input type="number" name="price_mng[selling_price][]" min="1" placeholder="Selling Price"/></td>
                                    <td><input type="button" id="delPOIbutton" value="Delete" onclick="deleteRow(this)"/></td>
                                </tr>
                            </table>
                            <input type="button" id="addmorePOIbutton" value="Add More" onclick="insRow()"/>

                        </div>

                    </div>  --}}
                    <div class="col-lg-12">
                        <div class="form-group" >
                            <label class="control-label" for="">Description</label>
                            <textarea id="editor11" name="description" class="form-control">{{ isset($category) ? $category->description : old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Price Manage</label>
                            <table id="POITable" class="table table-responsive">
                                <tr>
                                    <td>Sno</td>
                                    <td>City</td>
                                    <td>Price</td>
                                    <td>Discount(%)</td>
                                    <td>Delete</td>
                                </tr>
                                @if (isset($productprices))
                                <?php $ip=1; ?>
                                    @foreach ($productprices as $pp)
                                        <tr>
                                            <td>{{$ip++}}</td>
                                            <td>
                                                <select name="price_mng[location_id][]" class="form-control" required>
                                                    @if ($cities)
                                                        @foreach ($cities as $ct)
                                                            <option value="{{$ct->id}}"  {{$pp->location_id == $ct->id ? 'selected' : ''}}>{{$ct->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>

                                            <td><input type="number" class="form-control" name="price_mng[selling_price][]" value="{{$pp->mrp}}" min="1" placeholder="Price" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }"  required /></td>
                                            <td>
                                                <input type="number" class="form-control" name="price_mng[discount][]" value="{{$pp->discount}}" placeholder="Discount.." onkeyup="if(parseInt(this.value)>100 && parseInt(this.value)<0){ this.value =0; return false; } if(parseInt(this.value)<0){ this.value =0; return false; }" />
                                            </td>
                                            <td>
                                                {{-- <i class="fas fa-minus"  id="delPOIbutton" onclick="deleteRow(this)"></i> --}}
                                                <input type="button" class="btn btn-sm btn-warning" id="delPOIbutton" value="Delete" onclick="deleteRow(this)"/>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <select name="price_mng[location_id][]" class="form-control" required>
                                                @if ($cities)
                                                    @foreach ($cities as $ct)
                                                        <option value="{{$ct->id}}">{{$ct->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        
                                        <td><input type="number" class="form-control" name="price_mng[selling_price][]" min="1" placeholder="Price"required /></td>
                                        <td>
                                            <input type="number" class="form-control" name="price_mng[discount][]" placeholder="Discount.." />
                                        </td>
                                        <td>
                                            {{-- <i class="fas fa-minus"  id="delPOIbutton" onclick="deleteRow(this)"></i> --}}
                                            <input type="button" class="btn btn-sm btn-warning" id="delPOIbutton" value="Delete" onclick="deleteRow(this)"/>
                                        </td>
                                    </tr>
                                @endif
    
                            </table>
                            <input class="btn btn-sm btn-dark" type="button" id="addmorePOIbutton" value="Add More" onclick="insRow()"/>
                            {{-- <i style="cursor: pointer" class="fas fa-plus"  id="addmorePOIbutton" onclick="insRow()"></i> --}}
                        </div>
                        
                    </div>
                    <div class="col-lg-12">
                        
                        <div class="form-group" >
                            <input type="submit" name="Save" value="Save" class="btn btn-primary mt-3">
                        </div>
                    </div>
                </div>
               
            </form>
        </div>
    </div>
</div>

@section('scripts')
    <script>
fetch_subcategory();


    function deleteRow(row)
    {
        var i=row.parentNode.parentNode.rowIndex;
        // console.log('====================================');
        // console.log(i);
        // console.log('====================================');
        if(i!==1){
            document.getElementById('POITable').deleteRow(i);

        }else{
            alert('You cannot remove this row!')
        }
    }

    function insRow()
    {
        var x=document.getElementById('POITable');
        // deep clone the targeted row
        var new_row = x.rows[1].cloneNode(true);
        // console.log('====================================');
        // console.log(new_row);
        // console.log('====================================');
        // get the total number of rows
        var len = x.rows.length;
        // set the innerHTML of the first row 
        new_row.cells[0].innerHTML = len;

        // grab the input from the first cell and update its ID and value
        var inp1 = new_row.cells[2].getElementsByTagName('input')[0];
        inp1.id += len;
        inp1.value = '';

        // grab the input from the first cell and update its ID and value
        var inp2 = new_row.cells[3].getElementsByTagName('input')[0];
        inp2.id += len;
        inp2.value = '';

        // append the new row to the table
        x.appendChild( new_row );
    }

    </script>

@endsection

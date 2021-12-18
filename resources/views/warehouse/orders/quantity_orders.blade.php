@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        {{-- <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a href="{{route('create_coupon')}}" class="btn btn-primary  mb-2 mr-2" style="float: right">Add Coupon</a href="{{route('create_coupon')}}">
        </div> --}}
        @if ($errors->any())
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="alert alert-danger" style="width:100%">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        </div>
        @endif

       


        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{$page_title}}</h4>

                                 <form  method="GET">
                                 <div class="col-md-12" style="margin-bottom: 10px;">
                        <span>  <input type="submit" name="get_export_data" value="Export" class="btn btn-sm btn-success">
                        </span>
                    </div>
                             </form>

                             
                        </div>
                    </div>
                </div>
             
               
               
                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                
                                <th>Product</th>
                                <th>Unit</th>
                            
                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($fetchdata))
                          
                         
                                @for ($i=0;$i< count($fetchdata); $i++)
                                
                               
                             <?php if($fetchdata[$i]['product_id'] != @$fetchdata[$i+1]['product_id'] ) { ?>
                                    <tr>
                                        <?php
                                        $product_id = $fetchdata[$i]['product_id'];
                                         $product = DB::select("select * 
                                            from `products` where id =  $product_id");
                                          
                                        ?>
                                       
                                        <td><?php echo $product[0]->name ;?></td>
                                        <td>{{$fetchdata[$i]['unit']}}</td>
                                        
                                    
                                       
                                    </tr>
                                <?php } ?>
                              
                                @endfor
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                               
                               <th>Product</th>
                                <th>Unit</th>
                            </tr>
                        </tfoot>
                    </table>
                  
                </div>
            </div>
        </div>

    </div>

</div>
@endsection


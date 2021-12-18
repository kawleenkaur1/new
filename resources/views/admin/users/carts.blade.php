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
              


                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ProductID   </th>
                                <th>Img</th>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Added On </th>
                               
                            </tr>
                        </thead>
                        <tbody>



                            <?php $i = 1; ?>
                            <?php  $count =(($users->currentpage()-1)* $users->perpage() + 1); ?>

                            @foreach ($users as $user)

                                <tr>
                                    <td>{{$count++}}
                                    @if (date('Y-m-d') == date('Y-m-d',strtotime($user->created_at)))
                                        <span class="badge badge-danger">added today</span>
                                    @endif
                                    </td>
                                    <td class="text-center">{{$user->product_id}}</td>
                                      <td>
                                        <?php
                                         $product_id =   $user->product_id;
                                          $product = DB::table('products')->where('id',$product_id)->first();
                                          if ($product) {
                                            ?>
                                            <img style="width:80px;" class="usr-img rounded-circle" src="{{ asset('public/uploads/products/'.$product->image ) }}"  alt="{{$product->image}}">
                                            <?php
                                            
                                          }
                                            ?></td>
                                    <td>
                                        <?php
                                         $product_id =   $user->product_id;
                                          $product = DB::table('products')->where('id',$product_id)->first();
                                          if ($product) {
                                            echo $product->name;
                                          }
                                            ?></td>

                                  

                                          <td class="text-center">{{$user->qty}}</td>

                                   
                                    <td>{{date('d M y g:ia',strtotime($user->created_at))}}</td>


                                      

                                </tr>
                            @endforeach

                        </tbody>
                       

                    </table>
                    {{ $users->onEachSide(5)->links() }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection


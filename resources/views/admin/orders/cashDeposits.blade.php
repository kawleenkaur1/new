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

        <div id="view_details" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="load_modal">

                        </div>
                    </div>
                    <div class="modal-footer md-button">
                    </div>
                </div>
            </div>
        </div>
        <div id="view_user_details" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Header</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="load_modal">

                        </div>
                    </div>
                    <div class="modal-footer md-button">
                    </div>
                </div>
            </div>
        </div>
       

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{$page_title}}</h4>

                            
                        </div>
                    </div>
                  


                </div>


                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Delivery Boy Id</th>
                                <th>Delivery Boy</th>
                                <th>Warehouse Id</th>
                                <th>Warehouse</th>
                                <th>Amount</th>
                                <th>Mode</th>
                                <th>Txn Id</th>
                                <th>Document</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created</th>
                               
                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($fetchdata))
                            <?php  $count =(($fetchdata->currentpage()-1)* $fetchdata->perpage() + 1); ?>
                                @foreach ($fetchdata as $item)
                               
                                    <tr>
                                        <td>{{$count++}}
                                            @if (date('Y-m-d') == date('Y-m-d',strtotime($item->created_at)))
                                                <span class="badge badge-danger">added today</span>
                                            @endif

                                        </td>
                                        <td>{{$item->delivery_boy_id}}</td>
                                    
                                        <td>
                                        <?php
                                         $delivery_boy_id =   $item->delivery_boy_id;
                                          $delivery_boy = DB::table('users')->where('id',$delivery_boy_id)->first();
                                          if ($delivery_boy) {
                                            ?>
                                             <a style="color: red" href="{{route('fetch_deliveryboys')}}?q=<?=$delivery_boy->name?>" >{{$delivery_boy->name}}</a>
                                            <?php
                                           
                                          }
                                            ?>
                                                
                                            </td>
                                        <td>{{$item->warehouse_id}}</td>
                                        <td>
                                        <?php
                                         $warehouse_id =   $item->warehouse_id;
                                          $warehouse = DB::table('users')->where('id',$warehouse_id)->first();
                                          if ($warehouse) {
                                             ?>
                                             <a style="color: red" href="{{route('fetch_warehouses')}}?q=<?=$warehouse->name?>" >{{$warehouse->name}}</a>
                                            <?php

                                          
                                          }
                                            ?>
                                                
                                            </td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{$item->mode}}</td>
                                        <td>{{$item->txn_id}}</td>
                                        <td>
                                            <a href="{{ asset('public/uploads/cash_deposits/'.$item->document ) }}" target="_blank">
                                                

                                              <img style="width:80; height: 80px;" class="usr-img" src="{{ asset('public/uploads/cash_deposits/'.$item->document ) }}"  alt="{{$item->document}}">
                                            </a>

                                     
                                      </td>
                                        <td>{{$item->description}}</td>


                                         <td class="text-center"> 
                                             @if ($item->status == 1)
                                            <button type="button" class="btn btn-success btn-sm">Varified</button>
                                        @else
                                         <a href="{{route('cash_deposits_verified',['id'=>$item->id])}}" onclick="return confirm('Are you sure?')" ><button type="button" class="btn btn-primary btn-sm">Varified</button></a>
                                        @endif

                                           </td>

                                    
                                        
                                        <td>{{date('d M y g:i A',strtotime($item->created_at))}}</td>
                                       
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                    </table>
                    {{ $fetchdata->onEachSide(5)->links() }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection


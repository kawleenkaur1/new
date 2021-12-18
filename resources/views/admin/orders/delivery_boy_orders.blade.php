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
        <div id="edit" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Delivery Boy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="load_modal">Loading...</div>
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
                      <form  method="GET">
                    <div class="row">
                           <div class="col-md-12" style="margin-bottom: 10px;">
                        <span>  <input type="submit" name="get_export_data" value="Export" class="btn btn-sm btn-success">
                        </span>
                    </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                           
                                <div class="form-group mb-0 ">
                                    <input type="search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" class="form-control" placeholder="Search.." >
                                </div>
                                <div class="form-group mb-0 mt-2">
                                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    @if (isset($_GET['q']))
                                        <a href="{{isset($reset_link)?$reset_link:route('fetch_deliveries_by_deliveryboy')}}" class="btn btn-sm btn-dark">Clear</a>
                                    @endif

                                    {{-- <a href="{{isset($reset_link)?$reset_link:route('fetch_deliveries_by_deliveryboy')}}" class="btn btn-sm btn-dark">Reset</a> --}}

                                </div>
                            
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                           
                                

                                    <div class="form-group mb-0 ">
                                        <input id="rangeCalendarFlatpickr" value="<?=isset($_GET['search_date']) ? $_GET['search_date'] : '' ?>" name="search_date" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date.." >
                                    </div>
                                    <div class="form-group mb-0 mt-2">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                        @if (isset($_GET['search_date']))
                                            <a href="{{isset($reset_link)?$reset_link:route('fetch_deliveries_by_deliveryboy')}}" class="btn btn-sm btn-dark">Clear</a>
                                        @endif


                                    </div>
                               
                           
                        </div>
                         
                    </div>
                    </form>
                </div>


                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>OrderID</th>
                                <th>Name</th>
                                <th>Pincode</th>
                                <th>Address</th>
                                <th>Amount</th>
                                <th>DeliveryBoy</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Payment</th>
                                <th>Added</th>
                                <th class="no-content"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($fetchdata))
                            <?php  $count =(($fetchdata->currentpage()-1)* $fetchdata->perpage() + 1); ?>
                                @foreach ($fetchdata as $item)
                                <?php
                                $user_detail_url = route('get_user_details',['id'=>$item->user_id]);
                                    ?>
                                <div id="cancel_<?=$item->id?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cancel Order</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('cancel_order',['id'=>$item->id])}}" method="POST">
                                                    <div class="form-group">
                                                        <label for="">Cancel Reason</label>
                                                        <textarea name="cancel_reason" class="form-control"></textarea>
                                                    </div>
                                                    @csrf
                                                    <div class="form-group">
                                                        <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-sm btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="wallet_txn_<?=$item->id?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Refund</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('refund_for_order',['id'=>$item->id])}}" method="POST">
                                                    <div class="form-group">
                                                        <label for="">OrderID#</label>
                                                        <input type="number" name="amount" class="form-control" value="<?=$item->id?>" required readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Amount</label>
                                                        <input type="number" name="amount" class="form-control" value="<?=$item->payable_amount?>" required readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Message</label>
                                                        <textarea name="message" class="form-control"></textarea>
                                                    </div>
                                                    @csrf
                                                    <div class="form-group">
                                                        <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-sm btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- <div id="wallet_txn_<?=$item->id?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Wallet Txn</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="load_modal">
                                                    @include('admin.users.walletrec-form',['user'=>$item->user?$item->user:null])
                                                </div>
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                    <tr>
                                        <td>{{$count++}}
                                            @if (date('Y-m-d') == date('Y-m-d',strtotime($item->created_at)))
                                                <span class="badge badge-danger">added today</span>
                                            @endif

                                        </td>
                                        <td>#{{$item->id}}</td>
                                        <td><a style="color: red;cursor: pointer;" onclick="return get_user_details(<?=$item->user_id?>,'<?=$user_detail_url?>')" id="<?=$item->user_id?>">{{$item->user ? $item->user->name : ''}}</a></td>
                                        <td>{{$item->shipping_pincode}}</td>
                                        <td>{{$item->shipping_location}}</td>
                                        <td><?=rz_currency()?> {{$item->payable_amount}}</td>
                                        <td><?php echo $item->deliveryboy ? "<b>BuyOnce : </b>".$item->deliveryboy->name.'<br/>'.$item->deliveryboy->phone : ''; ?>
                                            <?php echo $item->subsdeliveryBoy ? "<br><b>Subscription : </b>".$item->subsdeliveryBoy->name.'<br/>'.$item->subsdeliveryBoy->phone : ''; ?>
                                        </td>
                                        <td>
                                            @if ($item->status == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($item->status == 1)
                                                <span class="badge badge-success">Confirmed</span>
                                            @elseif($item->status == 2)
                                                <span class="badge badge-primary">Delivered</span>
                                            @else
                                                @if ($item->is_refunded != 0)
                                                    <span class="badge badge-warning">Cancelled & Refunded</span>
                                                @else
                                                <span class="badge badge-danger">Cancelled</span>
                                                @endif

                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->order_type == 1)
                                                <span class="badge badge-dark">BuyOnce</span>
                                            @elseif($item->order_type == 2)
                                                <span class="badge badge-primary">Subscribe</span>
                                            @endif
                                        </td>
                                        <td  style="white-space:nowrap;">
                                            @if ($item->payment_mode == 'online')
                                                <span class="badge badge-success">Online</span>
                                            @elseif($item->payment_mode == 'cod')
                                                <span class="badge badge-primary">COD</span>
                                            @elseif($item->payment_mode == 'wallet')
                                                <span class="badge badge-warning">Wallet</span>
                                            @endif
                                            {{-- @if($item->is_paid == 2)
                                                <span class="badge badge-primary">Delivered</span>
                                            @else
                                                <span class="badge badge-danger">Cancelled</span>
                                            @endif --}}
                                            <?php if (($item->status == 3 || $item->status == 4)) : ?>
                                            <?php else: ?>
                                                <p>Payment Status : <br/><b><?=$item->is_paid==1?'PAID - '.rz_currency().' '.$item->paid_amount :'NOT PAID'?></b></p>
                                                <p><b>Pending : <b><?=$item->is_paid==0 ? rz_currency().' '.$item->payable_amount :  rz_currency().' '.$item->pending_amount?></b></b></p>
                                            <?php endif; ?>
                                        </td>
                                        <td>{{date('d M y g:i A',strtotime($item->created_at))}}</td>
                                        <td>

                                            <?php
                                            $url_ct = route('load_order_data',['id'=>$item->id]);
                                                ?>
                                            <button class="btn btn-sm btn-primary mb-2 mr-2" onclick="return load_data_view(<?=$item->id?>,'<?=$url_ct?>')" id="<?=$item->id?>">
                                                View</button>
                                            <?php
                                            $url_ct = route('load_assign_deliveryBoy_data',['id'=>$item->id]);
                                            ?>
                                          

                                            <a class="btn btn-sm btn-dark mb-2 mr-2" href="{{route('order_invoice',['id'=>$item->id])}}" >
                                                Inv</a>

                                            <?php if (($item->status == 3 || $item->status == 4) && $item->is_paid==1 && $item->is_refunded==0 ) : ?>
                                            <button class="btn btn-sm btn-warning mb-2 mr-2" data-toggle="modal" data-target="#wallet_txn_<?=$item->id?>">
                                                Refund</button>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>OrderID</th>
                                <th>Name</th>
                                <th>Pincode</th>
                                <th> Address</th>
                                <th>Amount</th>
                                <th>DeliveryBoy</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Payment</th>

                                <th>Added</th>
                                <th class="no-content"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{ $fetchdata->onEachSide(5)->links() }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection


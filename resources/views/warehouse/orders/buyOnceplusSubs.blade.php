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
        <div id="edit" class="modal fade" role="dialog">
            <div class="modal-dialog">
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
                            
                            <form  method="GET">
                                 <div class="col-md-12" style="margin-bottom: 10px;">
                        <span>  <input type="submit" name="get_export_data" value="Export" class="btn btn-sm btn-success">
                        </span>
                    </div>
                             </form>
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
                <div class="table-responsive mb-4 mt-4">
                    <table id="zero-config" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>OrderID</th>
                                <th>Product</th>
                                <th>QTY</th>
                                <th>Amount</th>
                                <th>Name</th>
                                <th>Pincode</th>
                                <th>DeliveryBoy</th>
                                {{-- <th>Status</th> --}}
                                <th>Added</th>
                                <th class="no-content"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($buyonces))
                            <?php  $count =1;
                            $badge_arr=['primary','success','warning','danger','dark'];
                            $change_id='';
                            ?>
                                @foreach ($buyonces as $item)
                                <?php
                                $user_detail_url = route('get_user_details',['id'=>$item->user_id]);
                                    ?>
                                <?php $change_id=$item->order_id; ?>
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
                                                        <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-sm btn-primary">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td><span class="badge badge-dark">#{{$item->order_id}}</span></td>
                                        <td>{{$item->product ? $item->product->name.' '.$item->actual_qty.' '.$item->unit : ''}}</td>                                        <td>{{$item->qty}}</td>
                                        <td><?=rz_currency()?> {{$item->price}}</td>

                                        <td><a style="color: red;cursor: pointer;" onclick="return get_user_details(<?=$item->user_id?>,'<?=$user_detail_url?>')" id="<?=$item->user_id?>">{{$item->user ?$item->user->name:'' }}</a></td>
                                        <td>{{$item->shipping_pincode}}</td>

                                        <td><?=$item->order->deliveryboy ? $item->order->deliveryboy->name.'<br/>'.$item->order->deliveryboy->phone : 'NA' ?></td>
                                        {{-- <td>
                                            @if ($item->status == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($item->status == 1)
                                                <span class="badge badge-success">Confirmed</span>
                                            @elseif($item->status == 2)
                                                <span class="badge badge-primary">Delivered</span>
                                            @else
                                                <span class="badge badge-danger">Cancelled</span>
                                            @endif
                                        </td> --}}
                                        <td>{{date('d M y g:i A',strtotime($item->created_at))}}</td>
                                        <td>

                                            <?php
                                            $url_ct = route('load_order_data',['id'=>$item->order_id]);
                                                ?>
                                            <button class="btn btn-sm btn-primary mb-2 mr-2" onclick="return load_data_view(<?=$item->order_id?>,'<?=$url_ct?>')" id="<?=$item->order_id?>">
                                                View</button>
                                            <?php
                                            $url_ct = route('load_assign_deliveryBoy_data',['id'=>$item->order_id]);
                                            ?>
                                            @if ($item->order->status == 1 || $item->order->status == 0 || $item->order->status == 2)
                                                {{-- <button class="btn btn-sm btn-dark mb-2 mr-2" onclick="return load_data_for_edit(<?=$item->order_id?>,'<?=$url_ct?>')" id="<?=$item->order_id?>">
                                                    Assign</button> --}}
                                                {{-- <button class="btn btn-sm btn-danger mb-2 mr-2" data-toggle="modal" data-target="#cancel_<?=$item->id?>">
                                                Cancel</button> --}}
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>OrderID</th>
                                <th>Product</th>
                                <th>QTY</th>
                                <th>Amount</th>
                                <th>Name</th>
                                <th>Pincode</th>
                                <th>DeliveryBoy</th>
                                {{-- <th>Status</th> --}}
                                <th>Added</th>
                                <th class="no-content"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- {{ $fetchdata->onEachSide(5)->links() }} --}}
                </div>
            </div>
        </div>





        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{$page_title_2}}</h4>
                        </div>
                    </div>
                </div>


                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>OrderID</th>
                                <th>Product</th>
                                <th>QTY</th>
                                <th>Amount</th>
                                <th>Deliveries</th>
                                <th>Start Date</th>
                                <th>Delivery</th>
                                <th>End Date</th>
                                <th>Name</th>
                                <th>Pincode</th>
                                <th>DeliveryBoy</th>
                                <th>Deliveries Dates</th>
                                <th>Vacations Dates</th>
                                <th>Skip Dates</th>


                                <th>Status</th>
                                {{-- <th>Added</th> --}}
                                <th class="no-content"></th>

                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($subscriptions))
                            <?php  $count =1;
                            $badge_arr=['primary','success','warning','danger','dark'];
                            $change_id='';
                            ?>
                                @foreach ($subscriptions as $item)
                                <div id="view_vacations_<?=$item->id?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Vacations</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php $vacation_arr=explode('|',$item->vacations);  ?>
                                                @if (!empty($vacation_arr))
                                                    @foreach ($vacation_arr as $v)
                                                    @if (!empty($v))
                                                    <span class="badge badge-dark mr-2 mb-2">{{date('d M Y',strtotime($v))}}</span>
                                                    @endif

                                                    @endforeach
                                                @else
                                                    No records found !
                                                @endif
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="view_skipdays_<?=$item->id?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Skip Dates</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php $skip_date_arr=explode('|',$item->skip_dates);  ?>
                                                @if (!empty($skip_date_arr))
                                                    @foreach ($skip_date_arr as $v)
                                                    @if (!empty($v))
                                                        <span class="badge badge-dark mr-2 mb-2">{{date('d M Y',strtotime($v))}}</span>
                                                    @endif
                                                    @endforeach
                                                @else
                                                    No records found !
                                                @endif
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="view_nonskipdays_<?=$item->id?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Deliveries Date</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php $non_skip_date_arr=explode('|',$item->non_skip_dates);  ?>
                                                @if (!empty($non_skip_date_arr))
                                                    @foreach ($non_skip_date_arr as $v)
                                                        @if (!empty($v))
                                                            <span class="badge badge-dark mr-2 mb-2" m-2>{{date('d M Y',strtotime($v))}}</span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                No records found !
                                                @endif
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <?php
                                    $user_detail_url = route('get_user_details',['id'=>$item->user_id]);
                                        ?>


                                        <?php $change_id=$item->order_id; ?>
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
                                                                <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-sm btn-primary">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer md-button">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $url_ct_1 = route('load_order_item_data',['id'=>$item->id]);
                                            ?>


                                            <tr>
                                                <td>{{$count++}}
                                                @if (date('Y-m-d') == date('Y-m-d',strtotime($item->created_at)))
                                                    <span class="badge badge-danger">added today</span>
                                                @endif
                                                </td>
                                                <td><span class="badge badge-dark">#{{$item->order_id}}</span></td>
                                                <td>{{$item->product ? $item->product->name.' '.$item->actual_qty.' '.$item->unit : ''}}</td>
                                                {{-- <td>{{$item->product ? $item->product->name.' '.$item->product->qty.' '.$item->product->unit : ''}}</td> --}}
                                                <td>{{$item->qty}}</td>
                                                <td><?=rz_currency()?> {{$item->price}}</td>
                                                <td><a data-toggle="modal" data-target="#view_nonskipdays_<?=$item->id?>" style="cursor: pointer"><span class="badge badge-success">{{$item->deliveries}}</span></a></td>


                                                <td><span class="badge badge-primary">{{date('d M Y',strtotime($item->start_date))}}</span></td>
                                                <td><span class="badge badge-dark">{{rz_frequency($item->skip_days)}}</span></td>
                                                <td><span class="badge badge-warning">
                                                    <?php echo date('d M Y',strtotime($item->additionals['end_date'])); ?>
                                                    </span>
                                                </td>
                                                <td><a style="color: red;cursor: pointer;" onclick="return get_user_details(<?=$item->user_id?>,'<?=$user_detail_url?>')" id="<?=$item->user_id?>">{{$item->shipping_name}}</a></td>
                                                <td><a style="color: red;cursor: pointer;" onclick="return load_data_view(<?=$item->id?>,'<?=$url_ct_1?>','view_order_item')" id="<?=$item->id?>">{{$item->shipping_pincode}}</a></td>

                                                <td><?=$item->order->subsdeliveryBoy ? $item->order->subsdeliveryBoy->name.'<br/>'.$item->order->subsdeliveryBoy->phone : 'NA' ?></td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#view_nonskipdays_<?=$item->id?>" class="btn btn-sm btn-primary">Open</a>
                                                </td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#view_vacations_<?=$item->id?>" class="btn btn-sm btn-primary">Open</a>

                                                </td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#view_skipdays_<?=$item->id?>" class="btn btn-sm btn-primary">Open</a>

                                                </td>

                                                <td>
                                                    @if ($item->status == 0)
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($item->status == 1)
                                                        <span class="badge badge-success">Confirmed</span>
                                                    @elseif($item->status == 2)
                                                        <span class="badge badge-primary">Delivered</span>
                                                    @else
                                                        <span class="badge badge-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>

                                                    <button class="btn btn-sm btn-success mb-2 mr-2" onclick="return load_data_view(<?=$item->id?>,'<?=$url_ct_1?>','view_order_item')" id="<?=$item->id?>">
                                                        Product</button>

                                                    <?php
                                                    $url_ct = route('load_order_data',['id'=>$item->order_id]);
                                                        ?>
                                                    <button class="btn btn-sm btn-primary mb-2 mr-2" onclick="return load_data_view(<?=$item->order_id?>,'<?=$url_ct?>')" id="<?=$item->order_id?>">
                                                        Order</button>
                                                    <?php
                                                    $url_ct = route('load_assign_deliveryBoy_data',['id'=>$item->order_id]);
                                                    ?>
                                                    @if ($item->order->status == 1 || $item->order->status == 0 || $item->order->status == 2)
                                                        {{-- <button class="btn btn-sm btn-dark mb-2 mr-2" onclick="return load_data_for_edit(<?=$item->order_id?>,'<?=$url_ct?>')" id="<?=$item->order_id?>">
                                                            Assign</button> --}}
                                                        {{-- <button class="btn btn-sm btn-danger mb-2 mr-2" data-toggle="modal" data-target="#cancel_<?=$item->id?>">
                                                        Cancel</button> --}}
                                                    @endif

                                                </td>
                                            </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>OrderID</th>
                                <th>Product</th>
                                <th>QTY</th>
                                <th>Amount</th>
                                <th>Deliveries</th>
                                <th>Start Date</th>
                                <th>Delivery</th>
                                <th>End Date</th>
                                <th>Name</th>
                                <th>Pincode</th>
                                <th>DeliveryBoy</th>
                                <th>Deliveries Dates</th>
                                <th>Vacations Dates</th>
                                <th>Skip Dates</th>


                                <th>Status</th>
                                {{-- <th>Added</th> --}}
                                <th class="no-content"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- {{ $fetchdata->onEachSide(5)->links() }} --}}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

<div id="view_order_item_2" class="modal fade" role="dialog">
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
<div id="view_order_item" class="modal fade" role="dialog">
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

<div id="view_order_item_1" class="modal fade" role="dialog">
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

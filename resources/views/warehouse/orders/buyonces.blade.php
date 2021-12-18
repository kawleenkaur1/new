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
                        </div>
                    </div>
                </div>
                <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                <form  method="GET">
                    <div class="form-group mb-0 ">
                        <input type="search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" class="form-control" placeholder="Search.." required>
                    </div>
                    <div class="form-group mb-0 mt-2">
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        <a href="{{route('fetch_buyonces')}}" class="btn btn-sm btn-dark">Reset</a>

                    </div>
                </form>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                 <form  method="GET">
                    
                                    <div class="form-group mb-0 ">
                                        <input id="rangeCalendarFlatpickr" value="<?=isset($_GET['search_date']) ? $_GET['search_date'] : '' ?>" name="search_date" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date.." required>
                                    </div>
                                    <div class="form-group mb-0 mt-2">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                        @if (isset($_GET['search_date']))
                                            <a href="{{isset($reset_link)?$reset_link:route('fetch_orders')}}" class="btn btn-sm btn-dark">Clear</a>
                                        @endif


                                    </div>
                </form>
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
                    <table class="table table-hover" style="width:100%">
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
                                <th>Status</th>
                                <th>Added</th>
                                <th class="no-content"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($fetchdata))
                            <?php  $count =(($fetchdata->currentpage()-1)* $fetchdata->perpage() + 1);
                            $badge_arr=['primary','success','warning','danger','dark'];
                            $change_id='';
                            ?>
                                @foreach ($fetchdata as $item)
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
                                        <td>{{$item->product ? $item->product->name.' '.$item->actual_qty.' '.$item->unit : ''}}</td>
                                        <td>{{$item->qty}}</td>
                                        <td><?=rz_currency()?> {{$item->price}}</td>

                                        <td><a style="color: red;cursor: pointer;" onclick="return get_user_details(<?=$item->user_id?>,'<?=$user_detail_url?>')" id="<?=$item->user_id?>">{{$item->user ?$item->user->name:'' }}</a></td>
                                        <td>{{$item->shipping_pincode}}</td>

                                        <td><?=$item->order->deliveryboy ? $item->order->deliveryboy->name.'<br/>'.$item->order->deliveryboy->phone : 'NA' ?></td>
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
                                <th>Status</th>
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


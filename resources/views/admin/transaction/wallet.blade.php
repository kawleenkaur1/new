@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('create_product')}}" style="float: right">Add Product</a>
        </div>
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


        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{$page_title}}</h4>
                        </div>
                    </div>
                </div>
                <form  method="GET">

                        <div class="col-md-12" style="margin-bottom: 10px;">
                        <span>  <input type="submit" name="get_export_data" value="Export" class="btn btn-sm btn-success">
                        </span>
                    </div>



                    <div class="form-group mb-0 ">
                        <input type="search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" class="form-control" placeholder="Search....">
                    </div>
                    <div class="form-group mb-0 mt-2">
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        @if (isset($_GET['q']))
                        <a href="{{isset($reset_link)? $reset_link : route('fetch_wallettxns')}}" class="btn btn-sm btn-dark">Clear</a>

                        @endif

                    </div>
                </form>
                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>TxnId</th>
                                <th>Old wallet</th>
                                <th>Txn</th>
                                <th>Updated Wallet</th>
                                <th>Txn Type</th>
                                <th>Txn For</th>
                                <th class="text-center">Status</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>


                            @if (!empty($fetchdata))
                                <?php  $count =(($fetchdata->currentpage()-1)* $fetchdata->perpage() + 1); ?>

                                @foreach ($fetchdata as $user)

                                    <tr>
                                        <td>{{$count++}}
                                            @if (date('Y-m-d') == date('Y-m-d',strtotime($user->created_at)))
                                            <span class="badge badge-danger">added today</span>
                                        @endif
                                        </td>
                                        <td>
                                            <?php
                                            $user_detail_url = route('get_user_details',['id'=>$user->user_id]);
                                                ?>
                                            <a style="color: red;cursor: pointer;" onclick="return get_user_details(<?=$user->user_id?>,'<?=$user_detail_url?>')" id="<?=$user->user_id?>">{{$user->user ? $user->user->name : ''}}</a>
                                        </td>
                                        <td>{{$user->order_txn_id}}</td>
                                        <td><span class="badge badge-dark">{{$user->old_wallet}}<span></td>
                                        <td><span class="badge badge-success">{{$user->txn_amount}}<span></td>
                                        <td><span class="badge badge-primary">{{$user->update_wallet}}</span></td>

                                        <td>
                                            @if ($user->type=='credit')
                                            <span class="badge badge-warning">{{$user->type}}</span>
                                            @elseif($user->type=='debit')
                                            <span class="badge badge-danger">{{$user->type}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->txn_for=='order')
                                            <?php
                                            $url_ct = route('load_order_data',['id'=>$user->order ? $user->order->id : 0]);
                                                ?>
                                                <a style="color: red;cursor: pointer;" onclick="return load_data_view(<?=$user->order ? $user->order->id : 0?>,'<?=$url_ct?>')" id="<?=$user->order ? $user->order->id : 0?>">Order</a>
                                                @else
                                                {{$user->txn_for}}
                                                @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($user->status == 1)
                                                <span class="badge badge-success">Success</span>
                                            @else
                                                @if ($user->status == 2)
                                                    <span class="badge badge-danger">Failed</span>
                                                @else
                                                    <span class="badge badge-warning">Failed</span>
                                                @endif
                                            @endif

                                        </td>
                                        <td>{{date('d M y',strtotime($user->created_at))}}</td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>TxnId</th>
                                <th>Old wallet</th>
                                <th>Txn</th>
                                <th>Updated Wallet</th>
                                <th>Txn Type</th>
                                <th>Txn For</th>
                                <th class="text-center">Status</th>
                                <th>Added</th>
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


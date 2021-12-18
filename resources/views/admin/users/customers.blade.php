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
                        <h5 class="modal-title">User Details</h5>
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

                            
                            <form  method="GET">
                                 <div class="col-md-12" style="margin-bottom: 5px;">
                        <span>  <input type="submit" name="get_export_data" value="Export" class="btn btn-sm btn-dark">
                        </span>
                    </div>
                             </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form  method="GET">
                                <div class="form-group mb-0 ">
                                    <input type="search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" class="form-control" placeholder="Search..">
                                </div>
                                <div class="form-group mb-0 mt-2">
                                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    @if (isset($_GET['q']))
                                        <a href="{{route('fetch_customers')}}" class="btn btn-sm btn-dark">Clear</a>
                                    @endif

                                    {{-- <a href="{{route('fetch_customers')}}" class="btn btn-sm btn-dark">Reset</a> --}}

                                </div>
                            </form>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form  method="GET">
                                {{-- <div class="widget-content widget-content-area"> --}}
                                    {{-- <p>Use <code>date with range</code> to search data.</p> --}}

                                    <div class="form-group mb-0 ">
                                        <input id="rangeCalendarFlatpickr" value="<?=isset($_GET['search_date']) ? $_GET['search_date'] : '' ?>" name="search_date" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                    </div>
                                    <div class="form-group mb-0 mt-2">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                        @if (isset($_GET['search_date']))
                                            <a href="{{route('fetch_customers')}}" class="btn btn-sm btn-dark">Clear</a>
                                        @endif


                                    </div>
                                {{-- </div> --}}
                            </form>
                        </div>
                    </div>
                </div>


                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Img</th>
                                <th>UserID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Wallet</th>
                                <th>Ref. Code</th>
                                <th>Registered</th>
                                <th>Cod</th>
                                <th>Orders</th>
                                <th>Spendings</th>
                                <th>Cart</th>
                                <th>Wishlists</th>
                                <th>LastLogin</th>
                                <th>Enable/Disable</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>



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

                            <?php $i = 1; ?>
                            <?php  $count =(($users->currentpage()-1)* $users->perpage() + 1); ?>

                            @foreach ($users as $user)

                                <?php  
                                $user_login = kt_userLastSeen($user->id);
                                ?>

                                <div id="wallet_txn_<?=$user->id?>" class="modal fade" role="dialog">
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
                                                    @include('admin.users.walletrec-form')
                                                </div>
                                            </div>
                                            <div class="modal-footer md-button">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <tr>
                                    <td>{{$count++}}
                                    @if (date('Y-m-d') == date('Y-m-d',strtotime($user->created_at)))
                                        <span class="badge badge-danger">added today</span>
                                    @endif
                                    </td>
                                    <td class="text-center"><img src="{{$user->image_url}}" style="width: 40px;" alt="{{$user->name}}"/></td>
                                    <td class="text-center">{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->phone}}</td>
                                    <td><?=rz_currency().''.$user->wallet?></td>
                                    <td>{{$user->referral_code}}</td>
                               

                                    <td>{{date('d M y g:ia',strtotime($user->created_at))}}</td>


                                         <td class="text-center"> 
                                             @if ($user->cod == 1)
                                            <a href="{{route('cod_user_disable',['id'=>$user->id])}}" onclick="return confirm('Are you sure?')" ><i class="fas fa-toggle-on"></i></a>
                                        @else
                                         <a href="{{route('cod_user_enable',['id'=>$user->id])}}" onclick="return confirm('Are you sure?')" ><i class="fas fa-toggle-off"></i></a>
                                        @endif

                                           </td>
                                        <td class="text-center">
                                           <a style="color: red" href="{{route('fetch_buyonce_orders')}}?user_id=<?=$user->id?>" >{{$user->orders ? $user->orders->count() : 0}}</a>
                                        </td>

                                        <td class="text-center">
                                            <?=$user->orders ? rz_currency().''.$user->orders->where('status',2)->sum('payable_amount') : 0?>
                                        </td>


                                        <td class="text-center">
                                           <a style="color: red" href="{{route('fetch_carts')}}?user_id=<?=$user->id?>" > {{$user->carts ? $user->carts->count() : 0}}</a>
                                        </td>

                                        <td class="text-center">
                                            <a style="color: red" href="{{route('fetch_list')}}?user_id=<?=$user->id?>" > {{$user->wishlists ? $user->wishlists->count() : 0}}</a>
                                         </td>
                                        
                                        <td >
                                            {{$user_login ? date('d M y g:ia',strtotime($user_login->created_at)) : ''}}
                                        </td>
                                        <td class="text-center"> 
                                            @if ($user->status == 1)
                                           <a href="{{route('user_disable',['id'=>$user->id])}}" onclick="return confirm('Are you sure?')" ><i class="fas fa-toggle-on"></i></a>
                                       @else
                                            <a href="{{route('user_enable',['id'=>$user->id])}}" onclick="return confirm('Are you sure?')" ><i class="fas fa-toggle-off"></i></a>
                                       @endif

                                          </td>


                                    <td class="text-center">
                                        @if ($user->status == 1)
                                            <center><i style='color:#0efa0e;' class='fas fa-circle green_circle'></i></center>
                                            {{-- <span class="badge badge-success">Active</span> --}}
                                        @else
                                            @if ($user->status == 2)
                                                {{-- <span class="badge badge-danger">Suspended</span> --}}
                                                <center><i style='color:red' class='fas fa-circle red_circle'></i></center>
                                            @else
                                                {{-- <span class="badge badge-warning">Inactive</span> --}}
                                                <center><i style='color:gray' class='fas fa-circle gray_circle'></i></center>

                                            @endif
                                        @endif

                                    </td>
                                    <td class="text-center">

                                        <?php
                                        $user_detail_url = route('get_user_details',['id'=>$user->id]);
                                            ?>
                                        {{-- <button type="button" class="btn btn-primary btn-sm" onclick="return get_user_details(<?=$user->id?>,'<?=$user_detail_url?>')" id="<?=$user->id?>">
                                        View</button> --}}

                                        <div class="dropdown custom-dropdown">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                <a style="cursor: pointer" class="dropdown-item" onclick="return get_user_details(<?=$user->id?>,'<?=$user_detail_url?>')" id="<?=$user->id?>">View User</a>
                                                <a style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#wallet_txn_<?=$user->id?>">Recharge Wallet</a>
                                                {{-- <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Delete</a> --}}
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        {{-- <tfoot>
                            <tr>
                                <th>#</th>
                                <th>CustomerID</th>

                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Wallet</th>
                                <th>Added</th>
                                <th>Cod</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot> --}}

                    </table>
                    {{ $users->onEachSide(5)->links() }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection


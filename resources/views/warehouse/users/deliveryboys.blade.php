@extends('admin.layouts.main')
@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('add_deliveryboywarehouse')}}" style="float: right">Add Delivery Boy</a>
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


        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{$page_title}}</h4>
                            <form  method="GET">
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <span>  
                                        <input type="submit" name="get_export_data" value="Export" class="btn btn-sm btn-dark">
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
                                        <a href="{{route('fetch_deliveryboys')}}" class="btn btn-sm btn-dark">Clear</a>
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
                                            <a href="{{route('fetch_deliveryboys')}}" class="btn btn-sm btn-dark">Clear</a>
                                        @endif


                                    </div>
                                {{-- </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive" style="margin-top: 2px;">
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Img</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Cash Hold</th>
                                    <th>Pincode</th>
                                    <th>Orders Pending</th>
                                    <th>Orders Delivered</th>
                                    <th>Registered</th>
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

                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>
                                            <div class="user-img">
                                                <img style="width:80px;" class="usr-img rounded-circle" style="width:40px;" src="{{$user->image_url}}" alt="{{$user->name}}">
                                            </div>
                                        </td>
                                        <td >{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td class="text-center"> 
                                            @if ($user->can_cash_hold == 1)
                                           <a href="{{route('can_cash_hold_disablewarehouse',['id'=>$user->id])}}" onclick="return confirm('Are you sure?')" ><i class="fas fa-toggle-on"></i></a>
                                       @else
                                        <a href="{{route('can_cash_hold_enablewarehouse',['id'=>$user->id])}}" onclick="return confirm('Are you sure?')" ><i class="fas fa-toggle-off"></i></a>
                                       @endif

                                        </td>
                                        <td>{{$user->pincode}}</td>
                                        <td>{{$user->deliveryboyorders ? $user->deliveryboyorders->whereIn('status',[0,1])->count() : 0}}</td>
                                        <td>{{$user->deliveryboyorders ? $user->deliveryboyorders->where('status',2)->count() : 0}}</td>
                                        <td>{{date('d M y g:ia',strtotime($user->created_at))}}</td>
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
                                            $user_detail_url = route('get_user_detailswarehouse',['id'=>$user->id]);
                                                ?>
                                            <a class="mb-2 mr-2" href="{{route('edit_deliveryboywarehouse',['id'=>$user->id])}}" ><i class="fas fa-edit"></i></a>

                                            <a style="cursor: pointer" class="mb-2 mr-2" onclick="return get_user_details(<?=$user->id?>,'<?=$user_detail_url?>')" id="<?=$user->id?>">
                                            <i class="fas fa-eye"></i></a>


                                            <div class="dropdown custom-dropdown">
                                                {{-- <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a> --}}

                                                {{-- <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div> --}}
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            
                        </table>
                        {{ $users->links() }}

                    </div>
            </div>
        </div>
    </div>

</div>

@endsection

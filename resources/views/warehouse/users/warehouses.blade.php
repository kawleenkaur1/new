@extends('admin.layouts.main')
@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('add_warehouse')}}" style="float: right">Add Warehouse</a>
        </div>


        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{$page_title}}</h4>
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
                </div>
                {{-- <form  method="GET">
                    <div class="widget-content widget-content-area">
                        <p>Use <code>date with range</code> to search data.</p>

                        <div class="form-group mb-0 ">
                            <input id="rangeCalendarFlatpickr" value="" name="search_date" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                        </div>
                        <div class="form-group mb-0 mt-2">
                            <button type="submit" class="btn btn-sm btn-success">Submit</button>
                            <a href="{{route('fetch_warehouses')}}" class="btn btn-sm btn-dark">Reset</a>

                        </div>
                    </div>
                </form> --}}
                <div class="">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Img</th>
                                    <th>WarehouseId</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Pincode</th>
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
                                                <img style="width:50px;" class="usr-img rounded-circle" src="{{$user->image_url}}" alt="{{$user->name}}">
                                            </div>
                                        </td>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>{{$user->city}}</td>
                                        <td>{{$user->state}}</td>
                                        <td>{{$user->pincode}}</td>
                                        <td>{{date('d M y g:i A',strtotime($user->created_at))}}</td>
                                        <td class="text-center">
                                            @if ($user->status == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                @if ($user->status == 2)
                                                    <span class="badge badge-danger">Suspended</span>
                                                @else
                                                    <span class="badge badge-warning">Inactive</span>
                                                @endif
                                            @endif

                                        </td>
                                        <td class="text-center">

                                            <?php
                                            $user_detail_url = route('get_user_details',['id'=>$user->id]);
                                                ?>
                                            {{-- <button type="button" class="btn btn-primary btn-sm" onclick="return get_user_details(<?=$user->id?>,'<?=$user_detail_url?>')" id="<?=$user->id?>">
                                            View</button> --}}
                                            {{-- <a href="{{route('edit_warehouse',['id'=>$user->id])}}" class="btn btn-sm btn-dark">Edit</a>
                                            <a  href="{{route('fetch_inventory_by_warehouse',['id'=>$user->id])}}" class="btn btn-sm btn-primary">Inventories</a> --}}

                                            <div class="dropdown custom-dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>
    
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                    <a style="cursor: pointer" class="dropdown-item" onclick="return get_user_details(<?=$user->id?>,'<?=$user_detail_url?>')" id="<?=$user->id?>">View</a>
                                                    <a style="cursor: pointer" href="{{route('edit_warehouse',['id'=>$user->id])}}" class="dropdown-item">Edit</a>
                                                    <a style="cursor: pointer" href="{{route('fetch_inventory_by_warehouse',['id'=>$user->id])}}" class="dropdown-item">Inventories</a>
                                                    {{-- <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a> --}}
                                                </div>
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

</div>

@endsection

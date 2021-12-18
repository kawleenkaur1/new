@extends('admin.layouts.main')
@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('add_cityadmin')}}" style="float: right">Add City Admin</a>
        </div>


        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{$page_title}}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-9 filtered-list-search mx-auto">
                            <form method="GET" class="form-inline my-2 my-lg-0 justify-content-center">
                                <div class="w-100">
                                    <input value="<?=isset($_GET['q']) ? $_GET['q'] : '' ?>" type="text" name="q" class="w-100 form-control product-search br-30" id="input-search" placeholder="Search User..." >
                                    <button class="btn btn-primary" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <form  method="GET">
                    <div class="widget-content widget-content-area">
                        <p>Use <code>date with range</code> to search data.</p>

                        <div class="form-group mb-0 ">
                            <input id="rangeCalendarFlatpickr" value="<?=isset($_GET['search_date']) ? $_GET['search_date'] : '' ?>" name="search_date" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                        </div>
                        <div class="form-group mb-0 mt-2">
                            <button type="submit" class="btn btn-sm btn-success">Submit</button>
                            <a href="{{route('fetch_cityadmins')}}" class="btn btn-sm btn-dark">Reset</a>

                        </div>
                    </div>
                </form>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Img</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Added</th>
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
                                                <img style="width:80px;" class="usr-img rounded-circle" src="{{$user->image_url}}" alt="{{$user->name}}">
                                            </div>
                                        </td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
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
                                            <button type="button" class="btn btn-primary btn-sm" onclick="return get_user_details(<?=$user->id?>,'<?=$user_detail_url?>')" id="<?=$user->id?>">
                                            View</button>
                                            <a href="{{route('edit_cityadmin',['id'=>$user->id])}}" class="btn btn-sm btn-dark">Edit</a>

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
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Img</th>

                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Added</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        {{ $users->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        {{-- <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('create_product')}}" style="float: right">Add Product</a>
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
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Referral From</th>
                                <th>Referral To</th>
                                <th>Earn Point</th>
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
                                            @if ($u=$user->referralfrom)
                                                <div class="user-img">
                                                    <img style="width:80px;" class="usr-img rounded-circle" src="{{$u->image_url}}" alt="img">
                                                </div>
                                                <p><b>Name : </b>{{$u->name}}</p>
                                                <p><b>Email : </b>{{$u->email}}</p>
                                                <p><b>Phone : </b>{{$u->phone}}</p>
                                            @else
                                            {{$user->refer_from}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($u=$user->referralto)
                                                <div class="user-img">
                                                    <img style="width:80px;" class="usr-img rounded-circle" src="{{$u->image_url}}" alt="img">
                                                </div>
                                                <p><b>Name : </b>{{$u->name}}</p>
                                                <p><b>Email : </b>{{$u->email}}</p>
                                                <p><b>Phone : </b>{{$u->phone}}</p>
                                            @else
                                            {{$user->refer_to}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$user->earn_points}}
                                        </td>

                                        <td>{{date('d M y',strtotime($user->created_at))}}</td>

                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Referral From</th>
                                <th>Referral To</th>
                                <th>Earn Point</th>
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


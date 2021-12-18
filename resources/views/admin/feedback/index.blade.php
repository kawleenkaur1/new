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
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form  method="GET">
                                {{-- <div class="widget-content widget-content-area"> --}}
                                    {{-- <p>Use <code>date with range</code> to search data.</p> --}}

                                    <div class="form-group mb-0 ">
                                        <input id="rangeCalendarFlatpickr" value="<?=isset($_GET['search_date']) ? $_GET['search_date'] : '' ?>" name="search_date" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date.." required>
                                    </div>
                                    <div class="form-group mb-0 mt-2">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                        @if (isset($_GET['search_date']))
                                            <a href="{{route('fetch_feedbacks')}}" class="btn btn-sm btn-dark">Clear</a>
                                        @endif


                                    </div>
                                {{-- </div> --}}
                            </form>
                        </div>
                    </div>
                </div>


                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Experience</th>
                                <th>Message</th>
                                <th>Added</th>
                                <th class="no-content"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($fetchdata))
                            <?php  $count =(($fetchdata->currentpage()-1)* $fetchdata->perpage() + 1); ?>
                                @foreach ($fetchdata as $item)

                                    <tr>
                                        <td>{{$count++}}</td>
                                        <?php
                                        $user_detail_url = route('get_user_details',['id'=>$item->user_id]);
                                            ?>
                                        <td><a style="color: red;cursor: pointer;" onclick="return get_user_details(<?=$item->user_id?>,'<?=$user_detail_url?>')" id="<?=$item->user_id?>">{{$item->user ? $item->user->name : ''}}</a></td>
                                        <td>{{$item->experience}}</td>
                                        <td>{{$item->message}}</td>


                                        <td>{{date('d M y g:i A',strtotime($item->created_at))}}</td>
                                        <td>



                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Experience</th>
                                <th>Message</th>
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


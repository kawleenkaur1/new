@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a class="btn btn-primary  mb-2 mr-2"  style="float: right" href="{{route('add_location')}}">Add location</a>
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
                        <input type="search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" class="form-control" placeholder="Search.." >
                    </div>
                    <div class="form-group mb-0 mt-2">
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        @if (isset($_GET['q']))
                        <a href="{{route('fetch_locations')}}" class="btn btn-sm btn-dark">Clear</a>
                        @endif


                    </div>
                </form>
                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>Name</th> --}}
                                <th>Location</th>
                                <th>Pincode</th>
                                <th>Status</th>
                                <th>Position</th>
                                <th>Added</th>
                                <th class="no-content"></th>
                            </tr>
                        </thead>
                        <tbody>

                            <div id="add_location" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add location</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @include('admin.location.location-form')
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
                                            <h5 class="modal-title">Edit location</h5>
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
                            @if (!empty($fetchdata))
                            <?php  $count =(($fetchdata->currentpage()-1)* $fetchdata->perpage() + 1); ?>
                                @foreach ($fetchdata as $item)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        {{-- <td>{{$item->name}}</td> --}}
                                        <td>{{$item->location}}</td>
                                        <td><span class="badge badge-dark">{{$item->pincode}}</span></td>
                                        <td>
                                            @if ($item->status == 1)
                                            <span class="badge badge-success">Active</span>
                                            @else
                                                @if ($item->status == 2)
                                                    <span class="badge badge-danger">Deleted</span>
                                                @else
                                                    <span class="badge badge-warning">Disable</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{$item->position}}</td>
                                        <td>{{date('d M y',strtotime($item->created_at))}}</td>
                                        <td>
                                            <?php
                                            $url_ct = route('load_location_data',['id'=>$item->id]);
                                                ?>
                                            <a class="btn btn-sm btn-primary mb-2 mr-2" href="{{route('update_location',['id'=>$item->id])}}">
                                                Edit</a>
                                            <a class="btn btn-sm btn-danger mb-2 mr-2" onclick="return confirm('Are you sure ?')" href="{{route('delete_location',['id'=>$item->id])}}">
                                                Delete</a>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                {{-- <th>Name</th> --}}
                                <th>Location</th>
                                <th>Pincode</th>
                                <th>Status</th>
                                <th>Position</th>
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


@section('scripts')

@endsection

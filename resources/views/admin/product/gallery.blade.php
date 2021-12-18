@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <button class="btn btn-primary  mb-2 mr-2"data-toggle="modal" data-target="#add_vehicle_category" style="float: right">Add Image</button>
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
                
                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>

                            <div id="add_vehicle_category" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Image</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- @include('admin.category.category-form') --}}
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
                                            <h5 class="modal-title">Edit Image</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- <div class="load_modal">Loading...</div> --}}
                                        </div>
                                        <div class="modal-footer md-button">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $count = 1; ?>
                         
                                @foreach ($fetchdata as $item)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>
                                            <div class="user-img">
                                                <img style="width:150px;" class="usr-img rounded-circle" src="{{$item->image_url}}" alt="profile">
                                            </div>
                                        </td>

                    
                                        <td>

                                        </td>
                                    </tr>
                                @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection


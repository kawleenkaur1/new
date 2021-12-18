@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <a href="{{route('create_coupon')}}" class="btn btn-primary  mb-2 mr-2" style="float: right">Add Coupon</a href="{{route('create_coupon')}}">
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
                        <input type="search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" class="form-control" placeholder="Search.."  >
                    </div>
                    <div class="form-group mb-0 mt-2">
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        <a href="{{route('fetch_coupons')}}" class="btn btn-sm btn-dark">Reset</a>

                    </div>
                </form>
                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Discount</th>
                                <th>Position</th>
                                <th>Status</th>
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
                                        <td>{{$item->name}}</td>
                                        <td>
                                            @if ($item->type ==1)
                                            Percentage
                                            @else
                                            Flat
                                            @endif
                                        </td>
                                        <td>{{$item->discount}}</td>
                                        <td>{{$item->position}}</td>
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
                                        <td>{{date('d M y',strtotime($item->created_at))}}</td>
                                        <td>
                                            <?php
                                            $url_ct = route('load_category_data',['id'=>$item->id]);
                                                ?>
                                            <a href="{{route('edit_coupon',['id'=>$item->id])}}" class="btn btn-sm btn-primary mb-2 mr-2">
                                                Edit</a>
                                            <a class="btn btn-sm btn-danger mb-2 mr-2" onclick="return confirm('Are you sure ?')" href="{{route('delete_coupon',['id'=>$item->id])}}">
                                                Delete</a>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Discount</th>
                                <th>Position</th>
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


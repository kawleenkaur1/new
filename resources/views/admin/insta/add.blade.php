@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
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
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                        <form method="POST" action="{{route('store_instafeed')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label><b>Title</b></label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                           <div class="mb-3">
                                <label><b>Description</b></label>
                                <input type="text" name="description" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label><b>Image</b></label><br>
                                <input type="file" name="image" class="form-control"  required>
                            </div>
                            <div class="mb-3">
                                <label><b>Hyperlink</b></label>
                                <input type="text" name="hyperlink" class="form-control" required>
                            </div>
                           <div class="mb-3">
                                <label><b>Select Status</b></label>
                                <select name="status" required class="form-control" >
                                    <option selected>Select Status</option>
                                    <option value="0">InActive</option>
                                    <option value="1">Active</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><b>Position</b></label>
                                <input type="number" min="0" class="form-control" name="position">
                            </div>
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                        </form>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


@endsection
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
<form method="POST" action="{{route('update_instafeed',['id'=>$post->id])}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label><b>Title</b></label>
        <input type="text" name="title" value="{{$post->title}}"  class="form-control">
    </div>
    <div class="mb-3">
        <label><b>Description</b></label>
        <input type="text" name="description"  value="{{$post->description}}" class="form-control">
    </div>
    <div class="mb-3">
        <label><b>Image</b></label>
        <input type="file" name="image" value="{{$post->image}}" class="form-control">
    </div>
    <div class="mb-3">
        <label><b>Hyperlink</b></label>
        <input type="text" name="hyperlink"  value="{{$post->hyperlink}}" class="form-control">
    </div>
    <div class="mb-3">
        <select name="status" >
            <option selected>Select Status</option>
            <option value="0" {{(isset($post['status']) && $post['status']=="0")? 'selected' : ''}}>InActive</option>
            <option value="1" {{(isset($post['status']) && $post['status']=="1")? 'selected' : ''}}>Active</option>
        </select>
    </div>
    <div class="mb-3">
        <label><b>Position</b></label>
        <input type="number" name="position" value="{{$post->position}}"  class="form-control">
    </div>
    
    <input type="submit" name="update" value="Update" class="btn btn-primary">
</form>

@endsection
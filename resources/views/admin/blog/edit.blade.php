@extends('admin.layouts.main')
@section('content')
<form method="POST" action="{{route('update',['id'=>$post->id])}}" enctype="multipart/form-data>
    @csrf
    <div class="mb-3">
        <label><b>Title</b></label>
        <input type="text" name="title" value="{{$post->title}}"  class="form-control">
    </div>
    <div class="mb-3">
        <label><b>Image</b></label>
        <input type="file" name="image" value="{{$post->image}}" class="form-control">
    </div>
    <div class="mb-3">
        <label><b>Description</b></label>
        <input type="text" name="description"  value="{{$post->description}}" class="form-control">
    </div>
    <div class="mb-3">
        <label><b>Catagory_id</b></label>
        <input type="text" name="id" value="{{$post->difficulty}}"  class="form-control">
    </div>
    <div class="mb-3">
        <select name="status" >
            <option selected>Select Status</option>
            <option value="0" {{(isset($post['status']) && $post['status']=="0")? 'selected' : ''}}>InActive</option>
            <option value="1" {{(isset($post['status']) && $post['status']=="1")? 'selected' : ''}}>Active</option>
        </select>
    </div>
    
    <input type="submit" name="update" value="update" class="btn-btn-primary">
</form>

@endsection
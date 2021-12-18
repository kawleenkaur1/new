@extends('admin.layouts.main')
@section('content')
<form method="POST" action="{{route('store')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label><b>Title</b></label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label><b>Image</b></label>
        <input type="file" name="image" class="form-control" required>
    </div>
    <div class="mb-3">
        <label><b>Description</b></label>
        <input type="text" name="description" class="form-control" required>
    </div>
   <div class="mb-3">
        <label><b>Catagory_id</b></label>
        <input type="number" name="id"  class="form-control" required>
    </div>
    <div class="mb-3">
        <select name="status" required>
            <option selected>Select Status</option>
            <option value="0">InActive</option>
            <option value="1">Active</option>
        </select>
    </div>
    <input type="submit" name="insert" value="insert" class="btn-btn-primary">
</form>

@endsection
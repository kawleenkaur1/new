@extends('admin.layouts.main')
@section('content')
<table id="myTable" class="table table-bordered shadow text-center table-striped">
    <tr>
        <th>ID</th>
        <th>TITLE</th>
        <th>Image</th>
        <th>Description</th>
        <th>Catagory_Id</th>
        <th>Status</th>
        <th>Created_At</th>
        <th>Updated_At</th>
        <th>DELETE</th>
        <th>EDIT</th>

    </tr>
    @foreach ($post as $post)
      <tr>

          <td>{{$post->id}}</td>
          <td>{{$post->title}}</td>
          <td>{{$post->image}}</td>
          <td>{{$post->description}}</td>
          <td>{{$post->catagory_id}}</td>
          <td>{{$post->status}}</td>
         <td>{{$post->created_at}}</td>
         <td>{{$post->updated_at}}</td>
          <td><a href="{{route('delete',['id'=>$post->id])}}" onclick="return confirm('Are you sure you want to Delete?');" class="btn btn-danger">DELETE</td>
          <td><a href="{{route('edit',['id'=>$post->id])}}" onclick="return confirm('Are you sure you want to Edit?');" class="btn btn-success">EDIT</td>
        </tr>
    @endforeach()
    </table>
  
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script type="text/javascript" href="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="crossorigin="anonymous"></script>
    <script>
        $(document).ready( function () {
        $('#myTable').DataTable();
    } );
    </script>
@endsection

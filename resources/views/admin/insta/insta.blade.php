
@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
          
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('index_instafeed')}}" style="float: right">Add Instafeed</a>
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
                <table id="zero-config-2" class="table table-bordered shadow text-center table-striped">
    <tr>
        <th>ID</th>
        <th>TITLE</th>
        <th>Description</th>
        <th>Image</th>
        <th>Hyperlink</th>
        <th>Status</th>
        <th>Position</th>
        <th>Updated_At</th>
        <th>Action</th>
        

    </tr>
    @foreach ($post as $ps)
      <tr>

          <td>{{$ps->id}}</td>
          <td>{{$ps->title}}</td>
          <td>{{$ps->description}}</td>
          <td>{{$ps->image}}</td>
          <td>{{$ps->hyperlink}}</td>
         <td>{{$ps->status}}</td>
          <td>{{$ps->position}}</td>
        <td>{{$ps->updated_at}}</td>
          <td><a href="{{route('edit_instafeed',['id'=>$ps->id])}}" class="btn btn-primary">EDIT</a><a href="{{route('delete_instafeed',['id'=>$ps->id])}}" onclick="return confirm('Are you sure you want to Delete?');" class="btn btn-danger">DELETE</a> </td>
          
        </tr>
    @endforeach
    </table>
    

                </div>
            </div>
        </div>

    </div>

</div>

<!-- 
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script type="text/javascript" href="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="crossorigin="anonymous"></script>
    <script>
        $(document).ready( function () {
        $('#instafeed_table').DataTable();
    } );
    </script> -->
@endsection


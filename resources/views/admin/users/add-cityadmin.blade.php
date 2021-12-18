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
        <div class="col-lg-12 col-md-12 layout-spacing">
            @include('admin.users.cityadmin-form')
        </div>
    </div>
</div>
@endsection

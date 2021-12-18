@extends('admin.layouts.main')
@section('content')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <?php if((isset($deal_product) && $deal_product)) : ?>
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('create_deal_product')}}" style="float: right">Add Product</a>
            <?php else : ?>
            <a class="btn btn-primary  mb-2 mr-2" href="{{route('create_product')}}" style="float: right">Add Product</a>
            <?php endif; ?>
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
                        <input type="search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" class="form-control" placeholder="Search by Product,Category,Subcategory.." >
                    </div>
                    <div class="form-group mb-0 mt-2">
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        @if (isset($_GET['q']))
                        <a href="{{isset($reset_link)? $reset_link : route('fetch_products')}}" class="btn btn-sm btn-dark">Clear</a>

                        @endif

                    </div>
                </form>
                <div class="table-responsive mb-4 mt-4">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ProductID</th>
                                <th>Img</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Net Wt</th>
                                {{-- <th>Subcategory</th> --}}
                                {{-- <th>Position</th> --}}
                                {{-- <th>Qty</th> --}}
                                {{-- <th>Selling</th> --}}
                                <th>Stock</th>
                                <th class="text-center">Status</th>
                                <th>Added</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @if (!empty($fetchdata))
                                <?php  $count =(($fetchdata->currentpage()-1)* $fetchdata->perpage() + 1); ?>

                                @foreach ($fetchdata as $user)

                                    <tr>
                                        <td>{{$count++}}
                                            @if (date('Y-m-d') == date('Y-m-d',strtotime($user->created_at)))
                                            <span class="badge badge-danger">added today</span>
                                        @endif
                                        </td>
                                        <td><span class="badge badge-dark">{{$user->id}}<span></td>
                                        <td>
                                            <div class="user-img">
                                                <img style="width:80px;" class="usr-img rounded-circle" src="{{$user->image_url}}" alt="{{$user->name}}">
                                            </div>
                                        </td>
                                        <td>{{$user->name}}</td>
                                        <td><span class="badge badge-dark">{{$user->category?$user->category->name:''}}<span></td>
                                        <td>{{ $user->net_wt }} {{ $user->unit }}</td>
                                        {{-- <td><span class="badge badge-primary">{{$user->subcategory?$user->subcategory->name:''}}</span></td> --}}
                                        {{-- <td>{{$user->position}}</td> --}}
                                        {{-- <td>{{$user->unit}}</td> --}}
                                        {{-- <td><?=rz_currency().' '.$user->selling_price?></td> --}}
                                        <td>{{get_product_stock($user->id)}}</td>
                                        <td class="text-center">
                                            <?php if($user->is_deal==1) : ?>

                                                <?php if(time() > strtotime($user->end_date)) :  ?>
                                                    <span class="badge badge-warning">Expired Deal</span>
                                                <?php else : ?>

                                                    @if ($user->status == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        @if ($user->status == 2)
                                                            <span class="badge badge-danger">Suspended</span>
                                                        @else
                                                            <span class="badge badge-warning">Inactive</span>
                                                        @endif
                                                    @endif

                                                <?php endif; ?>

                                            

                                            <?php else :  ?>

                                                @if ($user->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    @if ($user->status == 2)
                                                        <span class="badge badge-danger">Suspended</span>
                                                    @else
                                                        <span class="badge badge-warning">Inactive</span>
                                                    @endif
                                                @endif
                                            <?php endif; ?>
                                           

                                        </td>
                                        <td>{{date('d M y',strtotime($user->created_at))}}</td>
                                        <td class="text-center">

                                            <a href="{{route('manage_inventory',['id'=>$user->id])}}" class="btn btn-sm btn-warning">Add Stock</a>

                                            <a href="{{route('edit_product',['id'=>$user->id])}}" class="btn btn-sm btn-primary">Edit</a>

                                            <a href="{{route('delete_product',['id'=>$user->id])}}" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>


                                            <div class="dropdown custom-dropdown">

                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                       
                    </table>
                    {{ $fetchdata->onEachSide(5)->links() }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection


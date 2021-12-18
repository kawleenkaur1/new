

{{-- @if (isset($category)) --}}
<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            {{-- action="{{ /* route('update_vehicle_category',['id'=>$category->id])  */}}" --}}
        <form method="POST"  action="{{ isset($category) ? route('edit_banner',['id'=>$category->id]) : route('save_banner') }}" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="inputEmail4">Name</label>
                    <input type="text" name="name" value="{{ isset($category)? $category->name :old('name') }}" class="form-control"  placeholder="Name" required>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Position</label>
                    <input type="number" min="0" value="{{ isset($category)?$category->position:old('position') }}" class="form-control" name="position"  placeholder="Position" >
                </div>
                <div class="form-group">
                    <label for="inputState">Linking</label>
                    <select id="inputState" name="type" class="form-control" required>
                        <option value=""> Banner Linking...</option>
                        @if (isset($category))
                       <!--  <option value="1" {{$category->type == 1 ? 'selected' : ''}}>Top</option>
                        <option value="2" {{$category->type == 2 ? 'selected' : ''}}>Bottom</option> -->
                        <option value="3" {{$category->type == 3 ? 'selected' : ''}}>Category</option>

                        @else
                      <!--   <option value="1" {{old('type') == 1 ? 'selected' : ''}}>Top</option>
                        <option value="2" {{old('type') == 2 ? 'selected' : ''}}>Bottom</option> -->
                        <option value="3" {{old('type') == 3 ? 'selected' : ''}}>Category</option>

                        @endif

                    </select>
                </div>

                <div class="form-group">
                    <label for="inputState">Category</label>
                    <select  name="category_id" id="category" class="form-control category" required>
                        <option value="" selected>Choose...</option>
                        @if (!empty($categories))

                                @foreach ($categories as $it)

                                    @if (isset($category) && $category->link_type==2)
                                        <?php $selected_owner = $category->link_id == $it->id ? 'selected' : ''; ?>

                                    @elseif(isset($category) && $category->link_type==3)

                                        <?php $selected_owner = $category->link_parent_id == $it->id ? 'selected' : ''; ?>

                                    @else
                                        <?php $selected_owner =''; ?>
                                    @endif

                                    @if (old('category_id'))
                                        <?php $selected_owner = old('category_id') == $it->id ? 'selected' : ''; ?>
                                    @endif
                                    <option value="{{$it->id}}" {{$selected_owner}}>{{$it->name}}</option>
                                @endforeach

                        @endif
                    </select>
                </div>

            <!--     <div class="form-group">
                    <label for="inputState">Subcategory</label>
                    <select  name="subcategory_id" id="subcategory" class="form-control subcategory" required>
                        <option value="" selected>Choose...</option>
                        @if (!empty($subcategories))

                            @foreach ($subcategories as $it)

                                @if (isset($category) && $category->link_type==3)
                                    <?php $selected_owner = $category->link_id == $it->id ? 'selected' : ''; ?>
                                @else
                                    <?php $selected_owner =''; ?>
                                @endif

                                @if (old('category_id'))
                                    <?php $selected_owner = old('subcategory_id') == $it->id ? 'selected' : ''; ?>
                                @endif
                                <option value="{{$it->id}}" {{$selected_owner}}>{{$it->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
 -->

                @csrf
                <div class="form-group">
                    <label for="inputState">Status</label>
                    <select id="inputState" name="status" class="form-control" required>
                        <option value="">Choose...</option>
                        @if (isset($category))
                            <option value="1" {{$category->status == 1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{$category->status == 0 ? 'selected' : ''}}>Disable</option>
                        @else
                            <option value="1" {{old('status') == 1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : ''}}>Disable</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputState">Banner Type</label>
                    <select id="inputState" name="banner_type" class="form-control" required>
                        <option value="">Choose...</option>
                        @if (isset($category))
                            <option value="website" {{$category->banner_type == "website" ? 'selected' : ''}}>Website</option>
                            <option value="app" {{$category->banner_type == "app" ? 'selected' : ''}}>App</option>
                        @else
                            <option value="website" {{old('banner_type') == "website" ? 'selected' : ''}}>Website</option>
                            <option value="app" {{old('banner_type') == "app" ? 'selected' : ''}}>App</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Image</label>
                    <input type="file" name="image" onchange="readURL(this,'cat_image');" class="form-control" accept="image/*">
                    @if (isset($category))
                        <?php $img_url = $category->image_url; ?>
                    @else
                        <?php $img_url = false; ?>
                    @endif
                    <img id="cat_image" style="width: 100px;" src="{{$img_url}}" alt="your image" style="display: {{$img_url ? 'block' : 'none'}}" />
                </div>

              <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>


{{-- @else --}}
{{-- <div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{route('save_banner')}}" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="inputEmail4">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="inputEmail4" placeholder="Name" required>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Position</label>
                    <input type="number" min="0" value="{{ old('position') }}" class="form-control" name="position" id="inputEmail4" placeholder="Position" >
                </div>

                <div class="form-group">
                    <label for="inputState">Category</label>
                    <select  name="category_id" id="category" class="form-control" required>
                        <option value="" selected>Choose...</option>
                        @if (!empty($categories))

                            @foreach ($categories as $it)

                                @if (isset($category))
                                    <?php $selected_owner = $category->category_id == $it->id ? 'selected' : ''; ?>
                                @else
                                    <?php $selected_owner =''; ?>
                                @endif

                                @if (old('category_id'))
                                    <?php $selected_owner = old('category_id') == $it->id ? 'selected' : ''; ?>
                                @endif
                                <option value="{{$it->id}}" {{$selected_owner}}>{{$it->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

              <!--   <div class="form-group">
                    <label for="inputState">Subcategory</label>
                    <select  name="subcategory_id" id="subcategory" class="form-control" required>
                        <option value="" selected>Choose...</option>
                        @if (!empty($subcategories))

                            @foreach ($subcategories as $it)

                                @if (isset($category))
                                    <?php $selected_owner = $category->subcategory_id == $it->id ? 'selected' : ''; ?>
                                @else
                                    <?php $selected_owner =''; ?>
                                @endif

                                @if (old('category_id'))
                                    <?php $selected_owner = old('subcategory_id') == $it->id ? 'selected' : ''; ?>
                                @endif
                                <option value="{{$it->id}}" {{$selected_owner}}>{{$it->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div> -->
                @csrf

              <!--   <div class="form-group">
                    <label for="inputState">Type</label>
                    <select id="inputState" name="status" class="form-control" required>
                        <option value="">Choose Banner Type...</option>
                        <option value="1" {{old('type') == 1 ? 'selected' : ''}}>Top</option>
                        <option value="0" {{old('type') == 2 ? 'selected' : ''}}>Bottom</option>
                    </select>
                </div>
 -->
                <div class="form-group">
                    <label for="inputState">Status</label>
                    <select id="inputState" name="status" class="form-control" required>
                        <option value="">Choose...</option>
                        <option value="1" {{old('status') == 1 ? 'selected' : ''}}>Active</option>
                        <option value="0" {{old('status') == 0 ? 'selected' : ''}}>Disable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="inputState">Banner Type</label>
                    <select id="inputState" name="banner_type" class="form-control" required>
                        <option value="">Choose...</option>
                   

                           <option value="website" {{old('banner_type') == "website" ? 'selected' : ''}}>Website</option>
                            <option value="app" {{old('banner_type') == "app" ? 'selected' : ''}}>App</option>


                    </select>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Image</label>
                    <input type="file" name="image" onchange="readURL(this,'cat_image');" class="form-control" accept="image/*">
                    <img id="cat_image" style="width: 100px;"  alt="your image" style="display: none" />
                </div>

              <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div> --}}
{{-- @endif --}}

<?php
if(isset($category)){
    $category_id='category'.$category->id;
    $subcategory_id='subcategory'.$category->id;

}else{
    $category_id="category";
    $subcategory_id="subcategory";
}
?>

@section('scripts')
    <script>
// fetch_subcategory("{{$category_id}}","{{$subcategory_id}}");
fetch_subcategory();

    </script>
@endsection

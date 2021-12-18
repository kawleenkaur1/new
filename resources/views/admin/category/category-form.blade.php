

@if (isset($category))
<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            {{-- action="{{ /* route('update_vehicle_category',['id'=>$category->id])  */}}" --}}
        <form method="POST"  action="{{ route('edit_category',['id'=>$category->id])  }}" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="inputEmail4">Name</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" id="inputEmail4" placeholder="Name" required>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Position</label>
                    <input type="number" min="0" value="{{ $category->position }}" class="form-control" name="position" id="inputEmail4" placeholder="Position" >
                </div>
                @csrf
                <div class="form-group">
                    <label >Show on Homepage Top</label>
                    <select name="show_homepage_top" class="form-control">
                        <option value="1" {{$category->show_homepage_top == 1 ? 'selected' : ''}}>Yes</option>
                        <option value="0" {{$category->show_homepage_top == 0 ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label >Show on Homepage Bottom</label>
                    <select  name="show_homepage_bottom" class="form-control">
                        <option value="1" {{$category->show_homepage_bottom == 1 ? 'selected' : ''}}>Yes</option>
                        <option value="0" {{$category->show_homepage_bottom == 0 ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputState">Status</label>
                    <select id="inputState" name="status" class="form-control" required>
                        <option value="">Choose...</option>
                        <option value="1" {{$category->status == 1 ? 'selected' : ''}}>Active</option>
                        <option value="0" {{$category->status == 0 ? 'selected' : ''}}>Disable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Image</label>
                    {{-- <input type="file" class="form-control" name="image" > --}}
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


@else
<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{route('save_category')}}" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="inputEmail4">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="inputEmail4" placeholder="Name" required>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Position</label>
                    <input type="number" min="0" value="{{ old('position') }}" class="form-control" name="position" id="inputEmail4" placeholder="Position" >
                </div>
                @csrf

                <div class="form-group">
                    <label >Show on Homepage Top</label>
                    <select name="show_homepage_top" class="form-control">
                        <option value="1" {{ old('show_homepage_top') == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('show_homepage_top') == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label >Show on Homepage Bottom</label>
                    <select  name="show_homepage_bottom" class="form-control">
                        <option value="1 {{ old('show_homepage_bottom') == 1 ? 'selected' : '' }}">Yes</option>
                        <option value="0" selected>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="inputState">Status</label>
                    <select id="inputState" name="status" class="form-control" required>
                        <option value="">Choose...</option>
                        <option value="1" {{old('status') == 1 ? 'selected' : ''}}>Active</option>
                        <option value="0" {{old('status') == 0 ? 'selected' : ''}}>Disable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="inputEmail4">Image</label>
                    {{-- <input type="file" class="form-control" name="image" > --}}
                    <input type="file" name="image" onchange="readURL(this,'cat_image');" class="form-control" accept="image/*">
                    <img id="cat_image" style="width: 100px;"  alt="your image" style="display: none" />
                </div>

              <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>
@endif



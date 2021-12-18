


<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{isset($unit) ? route('edit_unit',['id'=>$unit->id]) : route('save_unit')}}" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{ isset($unit)?$unit->name:old('name') }}" class="form-control"  placeholder="Name" required>
                </div>

                @csrf


                <div class="form-group">
                    <label for="inputState">Status</label>
                    <select id="inputState" name="status" class="form-control" required>
                        <option value="">Choose...</option>
                        @if (isset($faq))
                            <option value="1" {{$faq->status == 1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{$faq->status == 0 ? 'selected' : ''}}>Disable</option>
                        @else
                            <option value="1" selected>Active</option>
                            <option value="0" >Disable</option>
                        @endif

                    </select>
                </div>

              <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>



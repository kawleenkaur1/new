


<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{isset($faq) ? route('edit_faq',['id'=>$faq->id]) : route('save_faq')}}" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Question</label>
                    <input type="text" name="question" value="{{ isset($faq)?$faq->question:old('name') }}" class="form-control"  placeholder="Question" required>
                </div>

                <div class="form-group">
                    <label for="">Answer</label>
                    <textarea name="answer" placeholder="Answer....." class="form-control">{{ isset($faq)?$faq->answer:old('answer') }}</textarea>
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



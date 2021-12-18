<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{route('store_send_notification')}}" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="">User</label>
                        {{-- <select name="user_id" class="form-control basic"> --}}
                        <select name="user_id" class="form-control">

                            <option value="0">All</option>
                            @if (!empty($users))
                                @foreach ($users as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <input type="hidden" name="user_type" value="{{$user_type ? $user_type : 2}}">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" value="" class="form-control"  placeholder="Notification Title.." required>
                    </div>

                    <div class="form-group">
                        <label for="">Message</label>
                        <textarea name="body" class="form-control" placeholder="Place your message here..">{{old('body')}}</textarea>
                    </div>



                    @csrf


                    {{-- <div class="form-group" >
                        <label>Driving License Back</label>
                        <input type="file" name="driv_lic_back" onchange="readURL(this,'driv_lic_back_image');" class="form-control" accept="image/*">
                        @if (isset($user))
                            <?php //$img_url = $user->driv_lic_back_url; ?>
                        @else
                            <?php //$img_url = false; ?>
                        @endif
                        <img id="driv_lic_back_image" src="{{$img_url}}" alt="License Back image" style="display: {{$img_url ? 'block' : 'none'}}"/>
                    </div>
 --}}

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

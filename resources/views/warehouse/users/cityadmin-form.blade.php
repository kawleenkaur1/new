<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{isset($user) ? route('update_cityadmin',['id'=>$user->id]) : route('save_cityadmin')}}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" name="name" value="{{ isset($user) ? $user->name : old('name') }}" class="form-control"  placeholder="Name" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="email" name="email" value="{{ isset($user) ? $user->email : old('email') }}" class="form-control"  placeholder="Email" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Phone</label>
                        <input type="number" name="phone" value="{{ isset($user) ? $user->phone : old('phone') }}" class="form-control"  placeholder="Phone" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Pincode</label>
                        <input type="text" name="pincode" value="{{ isset($user) ? $user->pincode : old('pincode') }}" class="form-control"  placeholder="Pincode" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Password</label>
                        <input type="password" name="password" value="{{ isset($user) ? '' : old('password') }}" class="form-control"  placeholder="Password">
                    </div>

                    @csrf
                    <div class="form-group">
                        <label for="inputState">Status</label>
                        <select id="inputState" name="status" class="form-control" required>
                            <option value="">Choose...</option>
                            @if (isset($user))
                                <option value="1" {{$user->status == 1 ? 'selected' : ''}}>Approve</option>
                                <option value="0" {{$user->status == 0 ? 'selected' : ''}}>Unapprove</option>
                            @else
                                <option value="1" >Approve</option>
                                <option value="0" >Unapprove</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group" >
                        <label>Image</label>
                        @if (isset($user))
                            <?php $img_url = $user->image_url; ?>
                        @else
                            <?php $img_url = false; ?>
                        @endif
                        <input type="file" name="image" onchange="readURL(this,'user_image');" class="form-control" accept="image/*">
                        <img id="user_image" src="{{$img_url}}" alt="License Front image" style="display: {{$img_url ? 'block' : 'none'}}" />
                    </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

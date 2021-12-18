<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{isset($user) ? route('update_owner',['id'=>$user->id]) : route('save_owner')}}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" name="name" value="{{ isset($user) ? $user->name : old('name') }}" class="form-control"  placeholder="Name" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="email" name="email" value="{{ isset($user) ? $user->email : old('email') }}" class="form-control"  placeholder="Email" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Mobile</label>
                        <input type="number" name="phone" value="{{ isset($user) ? $user->phone : old('phone') }}" class="form-control"  placeholder="Phone" required>
                    </div>

                    {{-- <div class="form-group">
                        <label for="name">Password</label>
                        <input type="email" name="password" value="{{ isset($user) ? '' : old('password') }}" class="form-control"  placeholder="Password" required>
                    </div> --}}

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

                    <div class="custom-file-container" data-upload-id="myFirstImage">
                        <label>Image <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file" >
                            <input type="file" name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                    </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

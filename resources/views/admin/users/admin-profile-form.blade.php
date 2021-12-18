<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{route('update_admin_profile')}}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" name="name" value="{{ old('name') ?old('name'): $admin->name }}" class="form-control"  placeholder="Name" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="email" name="email" value="{{ old('email') ? old('email') : $admin->email  }}" class="form-control"  placeholder="Email" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Mobile</label>
                        <input type="number" name="phone" value="{{ old('phone') ? old('phone') : $admin->phone }}" class="form-control"  placeholder="Phone" required>
                    </div>

                    @csrf
                    <div class="form-group">
                        <label for="">Old Password</label>
                        <input type="password" name="old_password" class="form-control" value="{{old('old_password')}}" placeholder="Old Password..">
                    </div>

                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" value="{{old('password')}}" placeholder="New Password..">
                    </div>

                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" value="{{old('password_confirmation')}}" placeholder="Confirm Password..">
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

<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{ route('changePassword') }}" enctype="multipart/form-data">
                    

                  @csrf
                   

                    <div class="form-group">
                        <label for="name">Current Password</label>
                         <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autocomplete="current_password">
                    </div>

                    
  <div class="form-group">
                        <label for="name">New Password</label>
                       <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="password">
                    </div>
          
  <div class="form-group">
                        <label for="name">Confirmation Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="password_confirmation">
                    </div>

                    

              

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

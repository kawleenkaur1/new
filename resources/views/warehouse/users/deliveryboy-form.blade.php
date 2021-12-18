{{-- <div class="row"> --}}
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{isset($user) ? route('update_deliveryboywarehouse',['id'=>$user->id]) : route('save_deliveryboywarehouse')}}" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-6 col-lg-6">
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

<input type="hidden" name="warehouse_id" value="<?php echo $warehouses_id; ?>" class="form-control" >
<input type="hidden" name="status" value="{{ isset($user) ? 0 : old('status') }}" class="form-control"  >
                        

                        <div class="form-group">
                            <label for="name">Pincode</label>
                            <input type="text" name="pincode" value="{{ isset($user) ? $user->pincode : old('pincode') }}" class="form-control"  placeholder="Pincode" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Password</label>
                            <input type="password" name="password" value="{{ isset($user) ? '' : old('password') }}" class="form-control"  placeholder="Password" {{isset($user) ? '' : 'required'}} >
                        </div>

                        @csrf
                      <!--   <div class="form-group">
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
                        </div> -->

                        <div class="form-group" >
                            <label>Image</label>
                            @if (isset($user))
                                <?php $img_url = $user->image_url; ?>
                            @else
                                <?php $img_url = false; ?>
                            @endif
                            <input type="file" name="image" onchange="readURL(this,'user_image');" class="form-control" accept="image/*">
                            <img id="user_image" src="{{$img_url}}" alt="License Front image" style="display: {{$img_url ? 'block' : 'none'}};width:50px;" />
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="">Aadhar Number</label>
                            <input data-type="adhaar-number" maxlength="19" value="{{ isset($user) ? $user->aadhar_number : old('aadhar_number') }}"  class="form-control   regcom sample highlight-error" placeholder="Aadhar Number" name="aadhar_number" type="text" required>
                        </div>

                        <div class="form-group" >
                            <label>ID Proof(Aadhar)</label>
                            @if (isset($user))
                                <?php $img_url = $user->aadhar_image_url; ?>
                            @else
                                <?php $img_url = false; ?>
                            @endif
                            <input type="file" name="aadhar_image" onchange="readURL(this,'aadhar_image');" class="form-control" {{isset($user)?"":"required"}}>
                            <img id="aadhar_image" src="{{$img_url}}" alt="Aadhar image" style="display: {{$img_url ? 'block' : 'none'}};width:50px;" />
                        </div>

                        <div class="form-group">
                            <label for="">PAN Number</label>
                            <input class="form-control" name="pan_number" placeholder="PAN Number" value="{{ isset($user) ? $user->pan_number : old('pan_number') }}"  name="pan_number" type="text" required>
                        </div>
                        <div class="form-group" >
                            <label>PAN Image</label>
                            @if (isset($user))
                                <?php $img_url = $user->pan_image_url; ?>
                            @else
                                <?php $img_url = false; ?>
                            @endif
                            <input type="file" name="pan_image" onchange="readURL(this,'pan_image');" class="form-control" {{isset($user)?"":"required"}}>
                            <img id="pan_image" src="{{$img_url}}" alt="PAN image" style="display: {{$img_url ? 'block' : 'none'}};width:50px;" />
                        </div>

                        <div class="form-group">
                            <label for="">DL Number</label>
                            <input class="form-control" name="dl_number" placeholder="DL Number" value="{{ isset($user) ? $user->dl_number : old('dl_number') }}"  name="dl_number" type="text" required>
                        </div>
                        <div class="form-group" >
                            <label>DL Image</label>
                            @if (isset($user))
                                <?php $img_url = $user->dl_image_url; ?>
                            @else
                                <?php $img_url = false; ?>
                            @endif
                            <input type="file" name="dl_image" onchange="readURL(this,'dl_image');" class="form-control" {{isset($user)?"":"required"}}>
                            <img id="dl_image" src="{{$img_url}}" alt="DL image" style="display: {{$img_url ? 'block' : 'none'}};width:50px;" />
                        </div>
                    </div>
            
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>

                    </div>
                </div>

            </form>
        </div>
    </div>
{{-- </div> --}}

@section('scripts')
<script>
    $('[data-type="adhaar-number"]').keyup(function() {
  var value = $(this).val();
  value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
  $(this).val(value);
});

$('[data-type="adhaar-number"]').on("change, blur", function() {
  var value = $(this).val();
  var maxLength = $(this).attr("maxLength");
  if (value.length != maxLength) {
    $(this).addClass("highlight-error");
  } else {
    $(this).removeClass("highlight-error");
  }
});

</script>
@endsection
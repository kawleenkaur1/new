<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{route('settings_update')}}" enctype="multipart/form-data">


                    <div class="form-group">
                        <label for="name">App Name</label>
                        <input type="name" name="app_name" value="{{ isset($app_settings) ? $app_settings->app_name : old('app_name') }}" class="form-control"  placeholder="Name" required>
                    </div>
                    @csrf
                    <div class="form-group">
                        <label for="name">Support Phone</label>
                        <input type="number" name="support_phone" value="{{ isset($app_settings) ? $app_settings->support_phone : old('support_phone') }}" class="form-control"  placeholder="Support Phone" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Support Email</label>
                        <input type="text" name="support_email" value="{{ isset($app_settings) ? $app_settings->support_email : old('support_email') }}" class="form-control"  placeholder="Support Email" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Address</label>
                        <input type="text" name="address" value="{{ isset($app_settings) ? $app_settings->address : old('address') }}" class="form-control"  placeholder="Address" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Office Week</label>
                        <input type="text" name="office_week" value="{{ isset($app_settings) ? $app_settings->office_week : old('office_week') }}" class="form-control"  placeholder="Office Week" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Office Time</label>
                        <input type="text" name="office_time" value="{{ isset($app_settings) ? $app_settings->office_time : old('office_time') }}" class="form-control"  placeholder="Office Time" >
                    </div>
                    <div class="form-group">
                        <label for="name">Sunday Time</label>
                        <input type="text" name="sunday_time" value="{{ isset($app_settings) ? $app_settings->sunday_time : old('sunday_time') }}" class="form-control"  placeholder="Sunday Time" >
                    </div>


                    <div class="form-group" >
                        <label>Logo</label>
                        <input type="file" name="logo" onchange="readURL(this,'logo_image');" class="form-control" accept="image/*">
                        @if (isset($app_settings))
                            <?php $img_url = $app_settings->logo_url; ?>
                        @else
                            <?php $img_url = false; ?>
                        @endif
                        <img id="logo_image" style="width: 100px;" src="{{$img_url}}" alt="your image" style="display: {{$img_url ? 'block' : 'none'}}" />
                    </div>

                    <div class="form-group" >
                        <label>Favicon</label>
                        <input type="file" name="favicon" onchange="readURL(this,'favicon_image');" class="form-control" accept="image/*">
                        @if (isset($app_settings))
                            <?php $img_url = $app_settings->favicon_url; ?>
                        @else
                            <?php $img_url = false; ?>
                        @endif
                        <img id="favicon_image"  style="width: 100px;" src="{{$img_url}}" alt="polution image" style="display: {{$img_url ? 'block' : 'none'}}"/>
                    </div>



                    <div class="form-group" >
                        <label>About Us Video</label>
                        <input type="file" name="about_us_video" onchange="readURL(this,'about_us_video');" class="form-control" accept="image/*">
                        @if (isset($app_settings))
                            <?php $video_url = $app_settings->about_us_video; ?>
                        @else
                            <?php $video_url = false; ?>
                        @endif
                        <img id="about_us_video" style="width: 100px;" src="{{$video_url}}" alt="your image" style="display: {{$video_url ? 'block' : 'none'}}" />
                    </div>





                    <div class="widget-content widget-content-area">
                        <label for="">Terms & Conditions</label>
                        <textarea id="editor11" name="terms">{{isset($app_settings) ? $app_settings->terms : false}}</textarea>
                    </div>

                    <div class="widget-content widget-content-area">
                        <label for="">Privacy Policy</label>
                        <textarea id="editor12" name="privacy_policy">{{isset($app_settings) ? $app_settings->privacy_policy : false}}</textarea>
                    </div>

                    <div class="widget-content widget-content-area">
                        <label for="">About Us</label>
                        <textarea id="editor13" name="about_us">{{isset($app_settings) ? $app_settings->about_us : false}}</textarea>
                    </div>

                    <div class="widget-content widget-content-area">
                        <label for="">Shipping Policy</label>
                        <textarea id="editor14" name="shipping_policy">{{isset($app_settings) ? $app_settings->shipping_policy : false}}</textarea>
                    </div>
                    <div class="widget-content widget-content-area">
                        <label for="">Payment Policy</label>
                        <textarea id="editor15" name="payment_policy">{{isset($app_settings) ? $app_settings->payment_policy : false}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Delivery Charges</label>
                        <input type="number" value="{{ isset($app_settings) ? $app_settings->delivery_charges : old('delivery_charges') }}" min="0" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" name="delivery_charges" class="form-control" placeholder="Delivery charges">
                    </div>

                    <div class="form-group">
                        <label for="">Referral Reward</label>
                        <input type="number" value="{{ isset($app_settings) ? $app_settings->referral_rewards : old('referral_rewards') }}" min="0" onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }" name="referral_rewards" class="form-control" placeholder="Referral rewards">

                    </div>

                    <div class="form-group">
                        <label for="name">App Splashing Text</label>
                        <input type="name" name="splashing_text" value="{{ isset($app_settings) ? $app_settings->splashing_text : old('splashing_text') }}" class="form-control"  placeholder="App Splashing Text" >
                    </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

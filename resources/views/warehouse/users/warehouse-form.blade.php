{{-- <div class="row"> --}}
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{isset($user) ? route('update_warehousewarehouse',['id'=>$user->id]) : route('save_warehouse')}}" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Warehouse Name</label>
                            <input type="name" name="name" value="{{ isset($user) ? $user->name : old('name') }}" class="form-control"  placeholder="Warehouse Name" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Warehouse Email</label>
                            <input type="email" name="email" value="{{ isset($user) ? $user->email : old('email') }}" class="form-control"  placeholder="Warehouse Email" readonly="">
                        </div>

                        <div class="form-group">
                            <label for="name">Warehouse Phone</label>
                            <input type="number" name="phone" value="{{ isset($user) ? $user->phone : old('phone') }}" class="form-control"  placeholder="Warehouse Phone" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Password</label>
                            <input type="password" name="password" value="{{ isset($user) ? '' : old('password') }}" class="form-control"  placeholder="Password" {{isset($user) ? ''  : 'required'}}>
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

                       <!--  <div class="form-group">
                            <label for="">Allocated Pincodes (Orders comes from these pincodes only)</label>
                            <select  name="pincode_allowance[]"  class="form-control tagging" multiple="multiple" >
                                @if (!empty($pincodes))

                                    @foreach ($pincodes as $it)

                                        @if (isset($user))
                                            <?php $category_ids_staring = explode('|',$user->pincode_allowance); ?>

                                            <?php $selected_owner = in_array( $it,$category_ids_staring) ? 'selected' : ''; ?>
                                        @else
                                            <?php $selected_owner =''; ?>
                                        @endif

                                        @if (old('pincode_allowance'))
                                            <?php $selected_owner = old('pincode_allowance') == $it ? 'selected' : 'selected'; ?>
                                        @endif
                                        <option value="{{$it}}" {{$selected_owner}}>{{$it}}</option>
                                    @endforeach
                                @endif
                            </select>

                        </div> -->
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Warehouse City</label>
                            <input type="text" name="city" value="{{ isset($user) ? $user->city : old('city') }}" class="form-control"  placeholder="City" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Warehouse State</label>
                            <input type="text" name="state" value="{{ isset($user) ? $user->state : old('state') }}" class="form-control"  placeholder="State" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Warehouse Pincode</label>
                            <input type="text" name="pincode" value="{{ isset($user) ? $user->pincode : old('pincode') }}" class="form-control"  placeholder="Pincode" required>
                        </div>


                        <div class="form-group">
                            <label for="">Warehouse Location</label>
                            <div id="locationField">
                                <input id="rz_location-pac-input" value="{{ isset($user) ? $user->location: old('location') }}" type="text" placeholder="Enter a location" class="form-control" name="location" required>
                            </div>
                            <div id="rz_location-map" style="position: relative; overflow: hidden;">
                                <div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);">
                                <div style="overflow: hidden;"></div>

                                <div class="gm-style" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;">
                                    <div tabindex="0" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: pan-x pan-y;">
                                        <div style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);">
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;">
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                <div style="position: absolute; z-index: 987; transform: matrix(1, 0, 0, 1, -21, -245);">
                                                    <div style="position: absolute; left: 0px; top: 0px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>

                                                    </div>
                                                </div>
                                            </div>
                                            </div>

                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;"></div>
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;"></div>
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;"></div>
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 0;"></div>

                                        </div>

                                        <div class="gm-style-pbc" style="z-index: 2; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;">

                                            <p class="gm-style-pbt"></p>
                                        </div>

                                        <div style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;">
                                            <div style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);">

                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;"></div>
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;"></div>
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;"></div>
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;"></div>

                                            </div>
                                        </div>
                                    </div>

                                    <iframe aria-hidden="true" frameborder="0" tabindex="-1" style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe>

                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">Warehouse Latitude</label>
                            <input type="text" name="latitude" value="{{ isset($user) ? $user->latitude: old('latitude') }}" class="form-control drop_lat" id="input_lat"  placeholder="Latitude" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="">Warehouse Longitude</label>
                            <input type="text" name="longitude" value="{{ isset($user) ? $user->longitude: old('longitude') }}" class="form-control drop_lng"  placeholder="Longitude" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
{{-- </div> --}}

@include('admin.partials.latlon')


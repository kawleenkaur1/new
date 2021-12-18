<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{isset($location) ? route('edit_location',['id'=>$location->id]) :route('save_location')}}" enctype="multipart/form-data">
                {{-- <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{ isset($location) ? $location->name: old('name') }}" class="form-control"  placeholder="Name" required>
                </div> --}}

                <div class="form-group">
                    <label for="">Location</label>
                    <div id="locationField">
                        <input id="rz_location-pac-input" value="{{ isset($location) ? $location->location: old('location') }}" type="text" placeholder="Enter a location" class="form-control" name="location" required>
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
                    <label for="">Latitude</label>
                    <input type="text" name="lat" value="{{ isset($location) ? $location->lat: old('lat') }}" class="form-control drop_lat" id="input_lat"  placeholder="Latitude" readonly required>
                </div>

                <div class="form-group">
                    <label for="">Longitude</label>
                    <input type="text" name="lon" value="{{ isset($location) ? $location->lon: old('lon') }}" class="form-control drop_lng"  placeholder="Longitude" readonly required>
                </div>

                <div class="form-group">
                    {{-- <label for="">Pincode</label> --}}
                    <input type="hidden" name="pincode" value="{{ isset($location) ? $location->pincode: old('pincode') }}" class="form-control"  placeholder="Pincode" required>
                </div>
                <div class="form-group">
                    <label for="">Position</label>
                    <input type="number" min="0" value="{{isset($location) ? $location->position : old('position') }}" class="form-control" name="position" id="inputEmail4" placeholder="Position" >
                </div>
                @csrf

                <div class="form-group">
                    <label for="">Status</label>
                    <select  name="status" class="form-control" required>
                        <option value="">Choose...</option>
                        @if (isset($location))
                            <option value="1" {{$location->status == 1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{$location->status == 0 ? 'selected' : ''}}>Disable</option>
                        @else
                            <option value="1" {{old('status') == 1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : ''}}>Disable</option>
                        @endif
                    </select>
                </div>

              <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>
@include('admin.partials.latlon')

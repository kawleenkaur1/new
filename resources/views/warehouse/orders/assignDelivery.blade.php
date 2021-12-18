
<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
        <form method="POST"  action="{{route('assign_deliveryBoywarehouse',['id'=>$order_id])}}" enctype="multipart/form-data">

                @if ($check_if_have_buyonce)
                <div class="form-group">
                    <label for="inputState">Delivery Boy for BuyOnce</label>
                    <select id="inputState" name="delivery_boy_id" class="form-control" required>
                        <option value="">Choose Delivery Boy...</option>
                        @if (!empty($deliveryBoy))
                            @foreach ($deliveryBoy as $d)
                                <option value="{{$d->id}}">{{$d->name}} ({{$d->phone}})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @endif
                @if ($check_if_have_subscription)
                <div class="form-group">
                    <label for="inputState">Delivery Boy for Subscription</label>
                    <select id="inputState" name="deliver_boy_subscription_id" class="form-control" required>
                        <option value="">Choose Delivery Boy...</option>
                        @if (!empty($sbs_deliveryBoy))
                            @foreach ($sbs_deliveryBoy as $d)
                                <option value="{{$d->id}}">{{$d->name}} ({{$d->phone}})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @endif
                @csrf
              <button type="submit" class="btn btn-primary mt-3">Assign</button>
            </form>
        </div>
    </div>
</div>



<div id="ct" class="">
    <div class="invoice-00001">
        <div class="content-section  animated animatedFadeInUp fadeInUp">

            <div class="row inv--head-section">
{{--
                <div class="col-sm-6 col-12">
                    <h3 class="in-heading">Order Item</h3>
                </div>
                <div class="col-sm-6 col-12 align-self-center text-sm-right">
                </div> --}}

            </div>

            <div class="row inv--detail-section">

                <div class="col-sm-7 align-self-center">
                    <h5><b>Delivery Address</b></h5>
                    <p class="inv-customer-name">{{ucwords($order->user ? $order->user->name : '')}}</p>
                    <p class="inv-street-addr">{{trim($order->shipping_flat.' '.$order->shipping_location.' '.$order->shipping_pincode)}}</p>
                    <p class="inv-email-address">{{ucwords($order->user ? $order->user->email : '')}}</p>
                </div>
            </div>

            <div class="row inv--product-table-section">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="">
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Items</th>
                                    <th  scope="col">OrderType</th>
                                    <th  scope="col">Unit Price</th>
                                    <th  scope="col">Qty</th>
                                    <th  scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1;?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td>{{ucwords($order->product ? $order->product->name.' '.$order->actual_qty.' '.$order->unit : '')}}</td>

                                        <td >
                                            @if ($order->order_type == 1)
                                                <span class="badge badge-dark">BuyOnce</span>
                                            @elseif($order->order_type == 2)
                                                <span class="badge badge-warning">Subscribe</span>

                                            @endif
                                        </td>
                                        <td >
                                            @if ($order->order_type == 1)
                                            <?=rz_currency()?> {{($order->product ? $order->product->selling_price : 0)}}
                                            @elseif($order->order_type == 2)
                                            <?=rz_currency()?> {{($order->product ? $order->product->subscription_price : 0)}}

                                            @endif
                                            </td>
                                        <td >{{$order->qty}}</td>
                                        <td ><?=rz_currency()?> {{($order->price)}}</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


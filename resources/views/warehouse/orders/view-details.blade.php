<div id="ct" class="">
    <div class="invoice-00001">
        <div class="content-section  animated animatedFadeInUp fadeInUp">

            <div class="row inv--head-section">

                <div class="col-sm-6 col-12">
                    <h3 class="in-heading">Order Details</h3>
                </div>
                <div class="col-sm-6 col-12 align-self-center text-sm-right">
                    {{-- <div class="company-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hexagon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        <h5 class="inv-brand-name">CORK</h5>
                    </div> --}}
                </div>

            </div>
            <?php $paid_amount = $order->paid_amount;  ?>
            <?php $pending_amount = $order->pending_amount;  ?>

            <?php $is_subscription_order = 0;  ?>
            <?php $is_subs_all_delivered = 0;  ?>


            <div class="row inv--detail-section">

                <div class="col-sm-7 align-self-center">
                    <p class="inv-to">Invoice To</p>
                </div>
                <div class="col-sm-5 align-self-center  text-sm-right order-sm-0 order-1">
                    <p class="inv-detail-title">From : {{isset($yt_app_settings->app_name) ?$yt_app_settings->app_name: 'KATLEGO'}}</p>
                </div>
                {{-- check_if_have_subscription --}}
                <div class="col-sm-7 align-self-center">
                    <p class="inv-customer-name">{{ucwords($order->user ? $order->user->name : '')}}</p>
                    <p class="inv-email-address">{{ucwords($order->user ? $order->user->email : '')}}</p>
                    <b>Address</b>

                    <p class="inv-street-addr">{{trim($order->shipping_flat.' '.$order->shipping_landmark.' '.$order->shipping_area.' '.$order->shipping_location.' '.$order->shipping_pincode)}}</p>

                  
                </div>

                <div class="col-sm-5 align-self-center  text-sm-right order-2">
                    <p class="inv-list-number"><span class="inv-title">Invoice Number : </span> <span class="inv-number">[KT#{{$order->id}}]</span></p>
                    <p class="inv-created-date"><span class="inv-title">Invoice Date : </span> <span class="inv-date">{{date('d M Y',strtotime($order->created_at))}}</span></p>
                    {{-- <p class="inv-due-date"><span class="inv-title">Due Date : </span> <span class="inv-date">26 Aug 2019</span></p> --}}
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
                                @if (!empty($order->orders))
                                <?php $count = 1;?>
                                    @foreach ($order->orders as $o)
                                        {{-- @if ($o->status !=3 || $o->status !=4) --}}
                                            <tr>
                                                <td><?=$count++?></td>
                                                <td>{{ucwords($o->product ? $o->product->name.' '.$o->actual_qty.' '.$o->unit : '')}}</td>

                                                <td >
                                                    @if ($o->order_type == 1)
                                                        <span class="badge badge-dark">BuyOnce</span>
                                                    @elseif($o->order_type == 2)
                                                    <?php $is_subscription_order = 1; ?>
                                                        <span class="badge badge-warning">Subscribe</span>

                                                    @endif
                                                </td>
                                                <td >
                                                    @if ($o->order_type == 2)
                                                    <?=rz_currency()?> {{($o->product ? round($o->price/($o->qty*$o->deliveries)) : 0)}}</td>
                                                    @else
                                                    <?=rz_currency()?> {{($o->product ? round($o->price/$o->qty) : 0)}}</td>
                                                    @endif

                                                <td >{{$o->qty}}</td>
                                                <td ><?=rz_currency()?> {{($o->price)}}</td>
                                                
                                            </tr>
                                        {{-- @endif --}}
                                            <?php
                                            if($o->status ==3 || $o->status == 4){
                                                $order->payable_amount -= $o->price;
                                                $order->subtotal -= $o->price;

                                            }
                                            ?>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-sm-5 col-12 order-sm-0 order-1">
                    {{-- <div class="inv--payment-info">
                        <div class="row">
                            <div class="col-sm-12 col-12">
                                <h6 class=" inv-title">Payment Info:</h6>
                            </div>
                            <div class="col-sm-4 col-12">
                                <p class=" inv-subtitle">Bank Name: </p>
                            </div>
                            <div class="col-sm-8 col-12">
                                <p class="">Bank of India</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <p class=" inv-subtitle">Account Number : </p>
                            </div>
                            <div class="col-sm-8 col-12">
                                <p class="">1234567890</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-sm-7 col-12 order-sm-1 order-0">
                    <div class="inv--total-amounts text-sm-right">
                        <div class="row">
                            <div class="col-sm-8 col-7">
                                <p class="">Sub Total: </p>
                            </div>
                            <div class="col-sm-4 col-5">
                                <p class=""><?=rz_currency()?> {{$order->subtotal}}</p>
                            </div>
                            <div class="col-sm-8 col-7">
                                <p class="">Tax Amount: </p>
                            </div>
                            <div class="col-sm-4 col-5">
                                <p class=""><?=rz_currency()?> {{$order->gst}}</p>
                            </div>
                            <div class="col-sm-8 col-7">
                                <p class=" discount-rate">Discount : </p>
                            </div>
                            <div class="col-sm-4 col-5">
                                <p class=""><?=rz_currency()?> {{$order->discount}}</p>
                            </div>
                            <div class="col-sm-8 col-7">
                                <p class=" discount-rate">Delivery Charges : </p>
                            </div>
                            <div class="col-sm-4 col-5">
                                <p class=""><?=rz_currency()?> {{$order->delivery_charges}}</p>
                            </div>
                            <div class="col-sm-8 col-7 grand-total-title">
                                <h4 class="">Grand Total : </h4>
                            </div>
                            <div class="col-sm-4 col-5 grand-total-amount">
                                <h4 class=""><?=rz_currency()?> {{$order->payable_amount}}</h4>
                            </div>

                            <?php if ($is_subscription_order && $is_subs_all_delivered == 0): ?>
                            <div class="col-sm-8 col-7 ">
                                <h4 class="">Paid Amount : </h4>
                            </div>
                            <div class="col-sm-4 col-5 ">
                                <h4 class=""><?=rz_currency()?> {{$paid_amount}}</h4>
                            </div>

                            <div class="col-sm-8 col-7 ">
                                <h4 class="">Pending Amount : </h4>
                            </div>
                            <div class="col-sm-4 col-5 ">
                                <h4 class=""><?=rz_currency()?> {{$pending_amount}}</h4>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

















{{--
<div id="ct" class="">
    <div class="invoice-00001">
        <div class="content-section  animated animatedFadeInUp fadeInUp">

            <div class="row inv--head-section">

                <div class="col-sm-6 col-12">
                    <h3 class="in-heading">Order Details</h3>
                </div>
                <div class="col-sm-6 col-12 align-self-center text-sm-right">
                    <div class="company-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hexagon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        <h5 class="inv-brand-name">CORK</h5>
                    </div>
                </div>

            </div>

            <div class="row inv--detail-section">

                <div class="col-sm-7 align-self-center">
                    <p class="inv-to">Invoice To</p>
                </div>
                <div class="col-sm-5 align-self-center  text-sm-right order-sm-0 order-1">
                    <p class="inv-detail-title">From : XYZ Company</p>
                </div>

                <div class="col-sm-7 align-self-center">
                    <p class="inv-customer-name">Jesse Cory</p>
                    <p class="inv-street-addr">405 Mulberry Rd. Mc Grady, NC, 28649</p>
                    <p class="inv-email-address">redq@company.com</p>
                </div>
                <div class="col-sm-5 align-self-center  text-sm-right order-2">
                    <p class="inv-list-number"><span class="inv-title">Invoice Number : </span> <span class="inv-number">[invoice number]</span></p>
                    <p class="inv-created-date"><span class="inv-title">Invoice Date : </span> <span class="inv-date">20 Aug 2019</span></p>
                    <p class="inv-due-date"><span class="inv-title">Due Date : </span> <span class="inv-date">26 Aug 2019</span></p>
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
                                    <th class="text-right" scope="col">Qty</th>
                                    <th class="text-right" scope="col">Unit Price</th>
                                    <th class="text-right" scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Electric Shaver</td>
                                    <td class="text-right">20</td>
                                    <td class="text-right">$300</td>
                                    <td class="text-right">$2800</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Earphones</td>
                                    <td class="text-right">49</td>
                                    <td class="text-right">$500</td>
                                    <td class="text-right">$7000</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Wireless Router</td>
                                    <td class="text-right">30</td>
                                    <td class="text-right">$500</td>
                                    <td class="text-right">$3500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-sm-5 col-12 order-sm-0 order-1">
                    <div class="inv--payment-info">
                        <div class="row">
                            <div class="col-sm-12 col-12">
                                <h6 class=" inv-title">Payment Info:</h6>
                            </div>
                            <div class="col-sm-4 col-12">
                                <p class=" inv-subtitle">Bank Name: </p>
                            </div>
                            <div class="col-sm-8 col-12">
                                <p class="">Bank of America</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <p class=" inv-subtitle">Account Number : </p>
                            </div>
                            <div class="col-sm-8 col-12">
                                <p class="">1234567890</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 col-12 order-sm-1 order-0">
                    <div class="inv--total-amounts text-sm-right">
                        <div class="row">
                            <div class="col-sm-8 col-7">
                                <p class="">Sub Total: </p>
                            </div>
                            <div class="col-sm-4 col-5">
                                <p class="">$13300</p>
                            </div>
                            <div class="col-sm-8 col-7">
                                <p class="">Tax Amount: </p>
                            </div>
                            <div class="col-sm-4 col-5">
                                <p class="">$700</p>
                            </div>
                            <div class="col-sm-8 col-7">
                                <p class=" discount-rate">Discount : <span class="discount-percentage">5%</span> </p>
                            </div>
                            <div class="col-sm-4 col-5">
                                <p class="">$700</p>
                            </div>
                            <div class="col-sm-8 col-7 grand-total-title">
                                <h4 class="">Grand Total : </h4>
                            </div>
                            <div class="col-sm-4 col-5 grand-total-amount">
                                <h4 class="">$14000</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div> --}}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->float('subtotal',16,2)->default(0);
            $table->float('discount',16,2)->default(0);
            $table->float('coupon_discount',16,2)->default(0);
            $table->float('gst',16,2)->default(0);
            $table->float('delivery_charges',16,2)->default(0);
            $table->float('payable_amount',16,2)->default(0);
            $table->integer('coupon_id')->default(0);
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_flat')->nullable();
            $table->string('shipping_pincode')->nullable();
            $table->text('shipping_location')->nullable();
            $table->string('payment_mode')->default('online')->comment('cod,online');
            $table->unsignedBigInteger('delivery_boy_id')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0->pending,1->confirmed,2->delivered,3->cancel by user,4->cancel_by_admin');
            $table->tinyInteger('is_paid')->default(0)->comment('0->not_paid,1->paid');
            $table->unsignedBigInteger('txn_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

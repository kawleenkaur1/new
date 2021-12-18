<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('product_id')->default(0);
            $table->tinyInteger('order_type')->default(1)->comment('1->buyOnce,2->subscribe');
            $table->integer('qty')->default(1);
            $table->float('price',16,2)->default(0);
            $table->integer('deliveries')->default(1)->comment('this will eligible in case of order_type -> 2');
            $table->integer('deliveries_done')->default(1)->comment('this will eligible in case of order_type -> 2');

            $table->dateTime('start_date')->nullable()->comment('this will eligible in case of order_type -> 2');
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_flat')->nullable();
            $table->string('shipping_pincode')->nullable();
            $table->text('shipping_location')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('order_histories');
    }
}

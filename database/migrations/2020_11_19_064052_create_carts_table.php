<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('product_id')->default(0);
            $table->tinyInteger('order_type')->default(1)->comment('1->buyOnce,2->subscribe');
            $table->integer('deliveries')->default(1)->comment('this will eligible in case of order_type -> 2');
            $table->dateTime('start_date')->nullable()->comment('this will eligible in case of order_type -> 2');
            $table->unsignedBigInteger('address_id')->default(0)->comment('this will eligible in case of order_type -> 2');
            $table->integer('qty')->default(1);
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
        Schema::dropIfExists('carts');
    }
}

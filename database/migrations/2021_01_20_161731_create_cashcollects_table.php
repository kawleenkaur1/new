<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashcollectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashcollects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('deliveryboy_id')->default(0);
            $table->float('amount',16,2)->default(0);
            $table->tinyInteger('status')->default(0)->comment('0->collect,1->handover');
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
        Schema::dropIfExists('cashcollects');
    }
}

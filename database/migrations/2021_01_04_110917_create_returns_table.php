<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->default(0);
            $table->unsignedBigInteger('order_history_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('product_id')->default(0);
            $table->string('product_name')->nullable();
            $table->integer('qty')->default(1);
            $table->string('unit')->nullable();
            $table->float('amount',16,2)->default(0);
            $table->string('product_image')->nullable();
            $table->text('issue')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_refunded')->default(0);
            $table->integer('txn_id')->default(0);

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
        Schema::dropIfExists('returns');
    }
}

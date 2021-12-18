<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('flat')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();

            $table->string('main_location')->nullable();
            $table->string('main_society')->nullable();

            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();

            $table->text('location')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0->inactive,1->active,2->deleted');
            $table->tinyInteger('is_default')->default(0)->comment('0->no,1->yes');
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
        Schema::dropIfExists('addresses');
    }
}

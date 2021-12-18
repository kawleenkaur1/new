<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocietiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('societies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id')->default(0);
            $table->string('name')->nullable();
            $table->text('location')->nullable();
            $table->string('pincode')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('position')->default(0);
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
        Schema::dropIfExists('societies');
    }
}

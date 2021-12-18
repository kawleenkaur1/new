<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrequenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frequencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('days')->default(0);
            $table->text('description')->nullable();
            $table->integer('skip_days')->nullable();
            $table->integer('position')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0->inactive,1->active,2->deleted');
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
        Schema::dropIfExists('frequencies');
    }
}

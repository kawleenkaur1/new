<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id')->default(0)->comment('0->if not any linking, 1-link to source');
            $table->tinyInteger('link_type')->default(1)->comment('1->product,2-category');
            $table->string('name')->nullable();
            $table->string('image')->default('default.png');
            $table->integer('position')->default(0);
            $table->tinyInteger('type')->default(1)->comment('1->top,2->bottom');
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
        Schema::dropIfExists('banners');
    }
}

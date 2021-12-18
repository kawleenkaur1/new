<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->default(0);
            $table->string('name')->nullable();
            $table->string('image')->default('default.png');
            $table->integer('position')->default(0);
            $table->tinyInteger('any_discount')->default(0)->comment('0->no,1->yes');
            $table->float('discount',16,2)->default(0);
            $table->tinyInteger('status')->default(1)->comment('0->inactive,1->active,2->deleted');
            $table->unsignedBigInteger('added_by')->default(0);
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
        Schema::dropIfExists('subcategories');
    }
}

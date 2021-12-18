<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->default(0);
            $table->unsignedBigInteger('subcategory_id')->default(0);
            $table->string('name')->nullable();
            $table->string('image')->default('default.png');
            $table->float('mrp',16,2)->default(0);
            $table->float('discount',16,2)->default(0);
            $table->tinyInteger('discount_type')->default(1)->comment('1->%,2->flat');
            $table->float('selling_price',16,2)->default(0);
            $table->float('subscription_price',16,2)->default(0);
            $table->tinyInteger('show_in_subscriptions')->default(0)->comment('0->no,1->yes');
            $table->integer('stock')->default(0);
            $table->integer('qty')->default(0);
            $table->string('unit')->nullable();
            $table->integer('position')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0->inactive,1->active,2->deleted');
            $table->tinyInteger('mark_as_new')->default(0)->comment('0->no,1->yes');
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
        Schema::dropIfExists('products');
    }
}

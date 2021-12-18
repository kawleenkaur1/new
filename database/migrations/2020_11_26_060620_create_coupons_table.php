<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('use_limit')->default(1);
            $table->integer('position')->default(0);
            $table->tinyInteger('link_source')->default(0)->comment('1->product,2->category,3->subcategory');
            $table->unsignedBigInteger('link_id')->default(0)->comment('applicable only if have link');
            $table->tinyInteger('type')->default(1)->comment('1->%,2->flat');
            $table->float('discount',16,2)->default(0);
            $table->float('max_discount',16,2)->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('coupons');
    }
}

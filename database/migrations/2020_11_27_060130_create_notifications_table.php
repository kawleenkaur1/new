<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1)->comment('1->single,2->all');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->text('payload')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('show_in_app')->default(1);
            $table->tinyInteger('user_type')->default(2)->comment('2->users,3->delivery boy,4->city admin');
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
        Schema::dropIfExists('notifications');
    }
}

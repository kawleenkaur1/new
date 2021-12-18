<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_type')->default(2)->comment('1->admin,2->users,3->delivery_boy,4->cityadmin');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->default('default.png');
            $table->tinyInteger('email_verified')->default(0);
            $table->tinyInteger('phone_verified')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('referral_code')->nullable();
            $table->string('referral_from')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('location')->nullable();
            $table->string('pincode')->nullable();
            $table->string('device_id')->nullable();
            $table->string('device_type')->nullable();
            $table->string('auth_type')->default('normal');
            $table->text('device_token')->nullable();
            $table->string('model_name')->nullable();
            $table->string('password')->nullable();
            $table->float('wallet',16,2)->default(0);
            $table->tinyInteger('status')->default(1)->comment('0->inactive,1->active');
            $table->unsignedBigInteger('cityadmin_id')->default(0)->comment('applicable only in case of delivery boy');
            $table->unsignedBigInteger('added_by')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

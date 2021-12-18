<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('logo',255)->nullable();
            $table->string('favicon',255)->nullable();
            $table->string('support_email',255)->nullable();
            $table->string('support_phone',255)->nullable();
            $table->longText('terms')->nullable();
            $table->longText('privacy_policy')->nullable();
            $table->longText('about_us')->nullable();
            $table->longText('invite_friends')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}

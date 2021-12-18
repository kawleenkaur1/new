<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('order_id')->default(0);
            $table->string('order_txn_id')->nullable();
            $table->string('payment_mode')->default('online')->comment('online,cod,wallet');
            $table->string('type')->default('credit')->comment('credit,debit');
            $table->float('old_wallet',16,2)->default(0);
            $table->float('txn_amount',16,2)->default(0);
            $table->float('update_wallet',16,2)->default(0);
            $table->tinyInteger('status')->default(1)->comment('1->success,2->failed');
            $table->string('txn_mode')->default('other')->comment('credit_card,debit_card,ne_banking,UPI,other');
            $table->string('bank_name')->nullable();
            $table->string('bank_txn_id')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('account')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}

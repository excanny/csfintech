<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSagePayWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sage_pay_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('sage_pay_wallet_id');
            $table->integer('business_id');
            $table->double('amount');
            $table->double('prev_balance');
            $table->double('new_balance');
            $table->enum('type', ['DEBIT', 'CREDIT']);
            $table->longText('info');
            $table->string('reference');
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
        Schema::dropIfExists('sage_pay_wallet_transactions');
    }
}

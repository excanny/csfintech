<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet_id');
            $table->integer('business_id');
            $table->double('amount');
            $table->double('prev_balance');
            $table->double('new_balance');
            $table->enum('currency', ['NGN', 'USD'])->default('NGN');
            $table->enum('type', [ 'DEBIT', 'CREDIT' ])->nullable();
            $table->enum('product' ,['AIRTIME', 'DATA', 'ELECTRICITY', 'CABLE-TV', 'TRANSFER']);
            $table->longText('info');
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
        Schema::dropIfExists('commission_transactions');
    }
}

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
            $table->unsignedBigInteger('business_id');
            $table->double('amount');
            $table->double('net_amount');
            $table->double('charge');
            $table->enum('status', ['SUCCESSFUL', 'PENDING', 'FAILED']);
            $table->string('channel')->default('WEB');
            $table->string('reference');
            $table->enum('type', ['AIRTIME', 'DATA', 'ELECTRICITY', 'CABLE-TV', 'TRANSFER']);
            $table->boolean('wallet_debited')->nullable();
            $table->boolean('wallet_credited')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}

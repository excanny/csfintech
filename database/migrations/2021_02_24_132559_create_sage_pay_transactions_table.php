<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSagePayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sage_pay_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('access_code');
            $table->string('session_id');
            $table->string('aes_256_key')->nullable();
            $table->string('version')->nullable();
            $table->string('authentication_limit')->nullable();
            $table->string('ip_address');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->double('amount');
            $table->double('net_amount');
            $table->double('charge');
            $table->longText('info');
            $table->string('status');
            $table->string('channel')->default('WEB');
            $table->string('reference');
            $table->string('external_reference');
            $table->boolean('wallet_debited')->nullable();
            $table->boolean('wallet_credited')->nullable();
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
        Schema::dropIfExists('sage_pay_transactions');
    }
}

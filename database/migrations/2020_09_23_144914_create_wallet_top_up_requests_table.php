<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTopUpRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_top_up_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id');
            $table->double('amount');
            $table->string('name');
            $table->longText('info');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED']);
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
        Schema::dropIfExists('wallet_top_up_requests');
    }
}

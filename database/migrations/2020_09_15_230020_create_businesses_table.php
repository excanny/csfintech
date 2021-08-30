<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('info')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->enum('status', ['INITIATED', 'VERIFIED', 'ACTIVE', 'INACTIVE'])->default('INITIATED');
            $table->string('address_2')->nullable();
            $table->string('logo')->nullable();
            $table->string('charge_back_email')->unique()->nullable();
            $table->string('website')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Nigeria');
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('category')->nullable();
            $table->integer('size')->nullable();
            $table->string('bvn')->nullable();
            $table->enum('type', ['RC', 'BN', 'CAC_IT'])->nullable();
            $table->string('rc_number')->nullable();
            $table->string('rc_registration_document')->nullable();
            $table->string('bn_number')->nullable();
            $table->string('bn_registration_document')->nullable();
            $table->string('cac_it_number')->nullable();
            $table->string('cac_it_registration_document')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
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
        Schema::dropIfExists('businesses');
    }
}

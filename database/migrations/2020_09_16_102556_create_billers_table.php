<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('biller_type', ['AIRTIME', 'DATA', 'CABLE-TV', 'ELECTRICITY']);
            $table->string('provider');
            $table->string('biller_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('label1')->nullable();
            $table->string('narration')->nullable();
            $table->string('short_name')->nullable();
            $table->string('charge')->nullable();
            $table->string('type')->nullable();
            $table->string('logo_url')->nullable();
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
        Schema::dropIfExists('billers');
    }
}

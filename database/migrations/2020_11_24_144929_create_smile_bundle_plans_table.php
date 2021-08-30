<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmileBundlePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smile_bundle_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('allowance')->nullable();
            $table->string('price');
            $table->string('validity')->nullable();
            $table->string('datacode');
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
        Schema::dropIfExists('smile_bundle_plans');
    }
}

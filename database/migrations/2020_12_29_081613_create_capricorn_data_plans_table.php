<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapricornDataPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capricorn_data_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('service_type');
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
        Schema::dropIfExists('capricorn_data_plans');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCableTvBouquetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cable_tv_bouquets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['dstv', 'gotv', 'startimes']);
            $table->string('code');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('price');
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
        Schema::dropIfExists('cable_tv_bouquets');
    }
}

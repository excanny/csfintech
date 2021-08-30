<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyTypeColumnInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `transactions` CHANGE `type` `type` ENUM('AIRTIME', 'DATA', 'ELECTRICITY', 'CABLE-TV', 'TRANSFER', 'OTHERS') default 'OTHERS'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `transactions` CHANGE `type` `type` ENUM('AIRTIME', 'DATA', 'ELECTRICITY', 'CABLE-TV', 'TRANSFER')");
        });
    }
}

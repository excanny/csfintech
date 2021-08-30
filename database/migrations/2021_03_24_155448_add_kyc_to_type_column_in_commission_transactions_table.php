<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddKycToTypeColumnInCommissionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `commission_transactions` CHANGE `product` `product` ENUM('AIRTIME', 'DATA', 'ELECTRICITY', 'CABLE-TV', 'TRANSFER', 'KYC', 'WAEC', 'BULK-SMS', 'OTHERS') default 'OTHERS'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `commission_transactions` CHANGE `product` `product` ENUM('AIRTIME', 'DATA', 'ELECTRICITY', 'CABLE-TV', 'TRANSFER')");
        });
    }
}

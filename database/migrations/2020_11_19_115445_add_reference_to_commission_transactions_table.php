<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceToCommissionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('commission_transactions', 'reference')) {
                $table->string('reference')->nullable()->after('info');
            }
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
            if (Schema::hasColumn('commission_transactions', 'reference')) {
                $table->dropColumn('reference');
            }
        });
    }
}

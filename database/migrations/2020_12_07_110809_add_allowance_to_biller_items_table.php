<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowanceToBillerItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('biller_items', function (Blueprint $table) {
            if (!Schema::hasColumn('biller_items', 'allowance')) {
                $table->string('allowance')->nullable()->after('code');
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
        Schema::table('biller_items', function (Blueprint $table) {
            if (Schema::hasColumn('biller_items', 'allowance')) {
                $table->dropColumn('allowance');
            }
        });
    }
}

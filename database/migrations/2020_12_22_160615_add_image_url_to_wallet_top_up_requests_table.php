<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageUrlToWalletTopUpRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_top_up_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('wallet_top_up_requests', 'image_url')) {
                $table->string('image_url')->nullable()->after('status');
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
        Schema::table('wallet_top_up_requests', function (Blueprint $table) {
            if (Schema::hasColumn('wallet_top_up_requests', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });
    }
}

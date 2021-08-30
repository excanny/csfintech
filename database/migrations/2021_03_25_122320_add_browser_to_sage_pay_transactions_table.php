<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrowserToSagePayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sage_pay_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('sage_pay_transactions', 'browser')) {
                $table->string('browser')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('sage_pay_transactions', 'browser_details')) {
                $table->json('browser_details')->nullable()->after('browser');
            }
            if (!Schema::hasColumn('sage_pay_transactions', 'auth_in_process')) {
                $table->string('auth_status')->nullable()->after('browser_details');
            }
            if (!Schema::hasColumn('sage_pay_transactions', 'auth_html')) {
                $table->longText('auth_html')->nullable()->after('auth_status');
            }
            if (!Schema::hasColumn('sage_pay_transactions', 'pay_reference')) {
                $table->string('pay_reference')->nullable()->after('auth_html');
            }
            if (!Schema::hasColumn('sage_pay_transactions', 'display_success')) {
                $table->boolean('display_success')->default(false)->after('pay_reference');
            }
            if (!Schema::hasColumn('sage_pay_transactions', 'callback_url')) {
                $table->string('callback_url')->nullable()->after('display_success');
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
        Schema::table('sage_pay_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('sage_pay_transactions', 'browser')) {
                $table->dropColumn('browser');
            }
            if (Schema::hasColumn('sage_pay_transactions', 'browser_details')) {
                $table->dropColumn('browser_details');
            }
            if (Schema::hasColumn('sage_pay_transactions', 'auth_status')) {
                $table->dropColumn('auth_status');
            }
            if (Schema::hasColumn('sage_pay_transactions', 'auth_html')) {
                $table->dropColumn('auth_html');
            }
            if (Schema::hasColumn('sage_pay_transactions', 'pay_reference')) {
                $table->dropColumn('pay_reference');
            }
            if (Schema::hasColumn('sage_pay_transactions', 'display_success')) {
                $table->dropColumn('display_success');
            }
            if (Schema::hasColumn('sage_pay_transactions', 'callback_url')) {
                $table->dropColumn('callback_url');
            }
        });
    }
}

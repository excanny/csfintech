<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFeesAddPaymentGateway extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $fees = \App\Model\Fee::all();
        foreach ($fees as $fee) {
            $products = $fee->products;
            $newProducts = $products;
            array_push($newProducts, [
                "name" => "PAYMENT GATEWAY",
                "slug" => "payment_gateway",
                "status" => false,
                "cap" => 0,
                "charge" => 0,
                "charge_type" => "PERCENTAGE",
                "vas_commission" => 0,
                "merchant_commission" => 0
            ]);

            $fee->update([
                'products' => $newProducts
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

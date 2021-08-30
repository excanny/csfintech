<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFeesTable extends Migration
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
            $index = array_search('TRANSFER', array_column($products, 'name'));
            $transfer_products = $products[$index];
            $transfer_products['is_flat'] = false;
            $transfer_products['flat_charge'] = 0;
            $products[$index] = $transfer_products;
            $fee->update([
                'products' => $products
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

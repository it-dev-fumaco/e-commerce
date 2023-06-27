<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingConditionsColumnToShippingCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fumaco_shipping_product_category', function (Blueprint $table) {
            if (Schema::hasColumn('fumaco_shipping_product_category', 'condition')) return;
            $table->string('condition')->nullable();

            if (Schema::hasColumn('fumaco_shipping_product_category', 'qty')) return;
            $table->integer('qty')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fumaco_shipping_product_category', function (Blueprint $table) {
            $table->dropColumn('condition');
            $table->dropColumn('qty');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalAndDiscountToSaleMobiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_mobiles', function (Blueprint $table) {
             $table->decimal('selling_discounted_price', 12, 2)->after('selling_price')->nullable();
            $table->decimal('discount_share', 12, 2)->default(0)->after('selling_discounted_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_mobiles', function (Blueprint $table) {
            $table->dropColumn(['selling_discounted_price', 'discount_share']);
        });
    }
}

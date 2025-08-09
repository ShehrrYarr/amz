<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_mobiles', function (Blueprint $table) {
         $table->id();
    $table->unsignedBigInteger('sale_id');
    $table->unsignedBigInteger('mobile_id');
    $table->decimal('selling_price', 12, 2);

    $table->timestamps();

    $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
    $table->foreign('mobile_id')->references('id')->on('mobiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_mobiles');
    }
}

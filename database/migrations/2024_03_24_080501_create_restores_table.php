<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('mobile_name');
            $table->string('restore_by');
            $table->string('customer_name')->nullable();
            $table->string('imei_number');
            $table->decimal('old_cost_price', 12, 2);
            $table->decimal('old_selling_price', 12, 2);
            $table->decimal('new_cost_price', 12, 2);
            $table->decimal('new_selling_price', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restores');
    }
}

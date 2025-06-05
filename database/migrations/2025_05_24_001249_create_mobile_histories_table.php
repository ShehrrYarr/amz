<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_histories', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('mobile_id');
            $table->string('mobile_name');
            $table->string('created_by');
            
            $table->string('customer_name')->nullable();
            $table->string('battery_health')->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->string('availability_status'); // New column to track status
            $table->string('group'); 

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_histories');
    }
}

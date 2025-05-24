<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_records', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('from_user_id');
        $table->unsignedBigInteger('to_user_id');
        $table->unsignedBigInteger('mobile_id'); // Assuming you have a mobiles table

        // Add other necessary columns
        $table->timestamp('transfer_time')->nullable();
        $table->timestamps();

        $table->foreign('from_user_id')->references('id')->on('users');
        $table->foreign('to_user_id')->references('id')->on('users');
        $table->foreign('mobile_id')->references('id')->on('mobiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_records');
    }
}

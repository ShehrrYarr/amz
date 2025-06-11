<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('mobile_id');
            
            $table->string('category');

            $table->decimal('cost_price', 12)->nullable(); // Only for Purchase
            $table->decimal('selling_price', 12)->nullable();   // Only for Sale
            
            $table->timestamp('transaction_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // who performed the transaction

            $table->unsignedBigInteger('vendor_id')->nullable(); // vendor (supplier or buyer)
            $table->string('customer_name')->nullable();         // optional customer name

            $table->string('note')->nullable();

            $table->foreign('mobile_id')->references('id')->on('mobiles')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_transactions');
    }
}

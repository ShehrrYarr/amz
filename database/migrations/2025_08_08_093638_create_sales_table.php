<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
    $table->string('sale_type'); // 'vendor' or 'customer'
    $table->unsignedBigInteger('vendor_id')->nullable();
    $table->string('customer_name')->nullable();
    $table->unsignedBigInteger('sold_by');
    $table->decimal('total_amount', 12, 2);
    $table->decimal('discount', 12, 2)->default(0);
    $table->decimal('paid_amount', 12, 2);
    $table->decimal('due_amount', 12, 2)->default(0);
    $table->timestamp('sale_date')->useCurrent();
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
    $table->foreign('sold_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}

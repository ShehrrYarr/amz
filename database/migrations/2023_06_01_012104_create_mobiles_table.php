<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobiles', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_name');
            $table->string('imei_number')->unique();
            $table->string('sim_lock');
            $table->string('color');
            $table->string('storage');
            $table->decimal('cost_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            // $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('original_owner_id')->nullable();
            $table->string('battery_health')->nullable();

            $table->timestamps();
            $table->string('availability')->default('Available');

            $table->timestamp('sold_at')->nullable();
            $table->boolean('is_transfer')->default(false);
            $table->string('is_approve')->default('Not_Approved');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('original_owner_id')->references('id')->on('users')->onDelete('set null');


            // $table->unsignedBigInteger('vendor_id')->nullable();
            // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            // $table->unsignedBigInteger('sold_vendor_id')->nullable();
            // $table->foreign('sold_vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');

            $table->unsignedBigInteger('added_by')->nullable();       // who added
            $table->unsignedBigInteger('sold_by')->nullable();        // who sold
            $table->unsignedBigInteger('pending_by')->nullable();     // who marked as pending

            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sold_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pending_by')->references('id')->on('users')->onDelete('set null');


        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobiles');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponWholesaleCustomerNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_wholesale_customer_name', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mem_id')->length(20);
            $table->foreign('mem_id')
            ->references('mem_id')
            ->on('members')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('username',255)->nullable();
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
        Schema::dropIfExists('coupon_wholesale_customer_name');
    }
}

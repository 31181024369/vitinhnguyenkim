<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCoupondes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupondes', function (Blueprint $table) {
            $table->increments('idCouponDes')->length(11);
            $table->string('MaCouponDes', 250);
            $table->integer('SoLanSuDungDes')->length(11);
            $table->integer('SoLanConLaiDes')->length(11);
            $table->integer('StatusDes')->length(11)->default('0');
            $table->string('DateCreateDes', 150);
            $table->unsignedInteger('idCoupon')->length(11);
            $table->foreign('idCoupon')
            ->references('idCoupon')
            ->on('coupon')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->integer('Max')->length(11)->default('1');
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
        Schema::dropIfExists('coupondes');
    }
}

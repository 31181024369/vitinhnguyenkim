<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCoupondesusing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupondesusing', function (Blueprint $table) {
            $table->increments('IDCouponUs')->length(11);
            $table->integer('IDuser')->length(11)->default('0');
            $table->unsignedInteger('idCouponDes')->length(11);
            $table->foreign('idCouponDes')
            ->references('idCouponDes')
            ->on('coupondes')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('DateUsingCode',150);
            $table->string('IDOrderCode',250);
            $table->string('MaCouponUSer',150);
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
        Schema::dropIfExists('coupondesusing');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFlashSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_flash_sale', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->length(20);
            $table->foreign('product_id')
            ->references('product_id')
            ->on('product')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->double('price')->default('0');
            $table->double('price_old')->default('0');
            $table->integer('discount_percent')->default(0);
            $table->integer('discount_price')->default(0);
            $table->integer('time')->length(25)->default(0);
            $table->integer('status')->length(11)->nullable();
            $table->tinyInteger('adminid')->length(4)->default('1');
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
        Schema::dropIfExists('product_flash_sale');
    }
}

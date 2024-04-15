<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableListCartTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_cart_time', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->unsignedInteger('mem_id')->length(11);
            $table->foreign('mem_id')
            ->references('mem_id')
            ->on('members')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('MaKh',250);
            $table->unsignedBigInteger('product_id')->length(11);
            $table->foreign('product_id')
            ->references('product_id')
            ->on('product')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title', 255);
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
        Schema::dropIfExists('list_cart_time');
    }
}

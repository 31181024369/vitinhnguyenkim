<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductGiftDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_gift_description', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->length(20);
            $table->foreign('product_id')
            ->references('product_id')
            ->on('product')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->text('list_product')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('product_gift_description');
    }
}

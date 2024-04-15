<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price', function (Blueprint $table) {
            $table->bigIncrements('id')->length(20);
            $table->unsignedInteger('cat_id')->length(10);
            $table->foreign('cat_id')
            ->references('cat_id')
            ->on('price_category')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->length(20);
            $table->foreign('product_id')
            ->references('product_id')
            ->on('price_product')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('picture',250)->nullable()->default('NULL');
            $table->double('price')->default('0');
            $table->double('price_old')->default('0');
            $table->integer('main')->default('0');
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
        Schema::dropIfExists('price.');
    }
}

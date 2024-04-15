<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductPriceSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_search', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('title',150)->nullable()->default('NULL');
            $table->string('slug',250)->nullable()->default('');
            $table->string('price_min',150)->nullable()->default('0');
            $table->string('price_max',150)->nullable()->default('0');
            $table->tinyInteger('display')->length(4)->nullable()->default('1');
            $table->integer('menu_order')->length(11)->default('0');
            $table->integer('date_post')->length(11)->nullable()->default('0');
            $table->integer('date_update')->length(11)->default('0');
            $table->integer('adminid')->length(11);
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
        Schema::dropIfExists('product_price_search');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductCatSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cat_search', function (Blueprint $table) {
            $table->increments('op_id')->length(11);
            $table->unsignedInteger('cat_id')->length(11);
            $table->foreign('cat_id')
            ->references('cat_id')
            ->on('product_category')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->integer('parentid')->length(11)->default('0');
            $table->tinyInteger('input_text')->length(4)->nullable()->default('0');
            $table->integer('menu_order')->length(11)->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
            $table->integer('date_post',)->length(11);
            $table->integer('date_update')->length(11);
            $table->integer('adminid')->length(11)->default('1');
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
        Schema::dropIfExists('product_cat_search');
    }
}

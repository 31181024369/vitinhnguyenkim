<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductCatSearchDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cat_search_desc', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->unsignedInteger('op_id')->length(11);
            $table->foreign('op_id')
            ->references('op_id')
            ->on('product_cat_search')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title',150);
            $table->string('slug',250)->default('');
            $table->text('description')->nullable();
            $table->string('lang',50)->default('vi');
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
        Schema::dropIfExists('product_cat_search_desc');
    }
}

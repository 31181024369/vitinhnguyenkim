<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductShortcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_shortcode', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('title',250);
            $table->string('name',150)->nullable()->default('');
            $table->string('pids',250)->default('0');
            $table->integer('menu_order',)->length(11)->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
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
        Schema::dropIfExists('product_shortcode');
    }
}

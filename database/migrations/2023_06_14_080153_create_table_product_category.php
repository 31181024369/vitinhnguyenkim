<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->increments('cat_id')->length(11);
            $table->string('cat_code',150)->default('');
            $table->integer('parentid')->length(11)->default('0');
            $table->string('picture',150)->default('');
            $table->string('color',50)->default('000000');
            $table->string('psid',150)->nullable()->default('0');
            $table->tinyInteger('is_default')->length(3)->default('0');
            $table->tinyInteger('is_buildpc')->length(3)->default('0');
            $table->tinyInteger('show_home')->length(3)->default('0');
            $table->string('list_brand',250)->nullable()->default('NULL');
            $table->string('list_price',250)->default('0');
            $table->string('list_support',250)->nullable()->default('');
            $table->integer('menu_order')->length(11)->default('0');
            $table->integer('views')->length(11)->default('0');
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
        Schema::dropIfExists('product_category');
    }
}

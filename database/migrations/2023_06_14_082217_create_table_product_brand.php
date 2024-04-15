<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductBrand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_brand', function (Blueprint $table) {
            $table->increments('brand_id')->lenght(11);
            $table->unsignedInteger('cat_id')->lenght(11);
            $table->foreign('cat_id')
            ->references('cat_id')
            ->on('product_category')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('picture',150)->nullable()->default('NULL');
            $table->tinyInteger('focus')->lenght(4)->default('0');
            $table->integer('menu_order')->lenght(11)->default('0');
            $table->integer('views')->lenght(11)->default('0');
            $table->tinyInteger('display')->lenght(4)->default('1');
            $table->integer('date_post')->lenght(11);
            $table->integer('date_update')->lenght(11);
            $table->integer('adminid')->lenght(11)->default('1');
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
        Schema::dropIfExists('product_brand');
    }
}

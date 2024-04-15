<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductPicture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_picture', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->bigInteger('pid')->length(20)
            ->references('product_id')
            ->on('product')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('pic_name',150)->nullable()->default('NULL');
            $table->string('picture',150);
            $table->integer('menu_order')->length(11)->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
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
        Schema::dropIfExists('product_picture');
    }
}

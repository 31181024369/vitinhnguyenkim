<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFaqsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs_category', function (Blueprint $table) {
            $table->increments('cat_id')->length(11);
            $table->string('cat_code',150)->default('');
            $table->integer('parentid')->length(11)->default('0');
            $table->string('picture',150)->default('');
            $table->tinyInteger('is_default')->length(4)->default('0');
            $table->tinyInteger('show_home')->length(4)->default('0');
            $table->integer('focus_order')->length(11)->default('0');
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
        Schema::dropIfExists('faqs_category');
    }
}

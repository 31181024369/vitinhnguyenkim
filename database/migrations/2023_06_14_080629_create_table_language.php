<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language', function (Blueprint $table) {
            $table->increments('lang_id')->length(11);
            $table->string('name',100);
            $table->string('meta_lang',50)->nullable()->default('vi_VN');
            $table->string('title',250);
            $table->string('picture',150)->nullable()->default('NULL');
            $table->string('date_format',150)->nullable()->default('NULL');
            $table->string('time_format',150)->nullable()->default('NULL');
            $table->string('unit',50)->nullable()->default('NULL');
            $table->string('num_format',150)->nullable()->default('NULL');
            $table->tinyInteger('is_default')->length(4)->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
            $table->integer('menu_order')->length(11)->default('0');
            $table->integer('date_post')->length(11)->default('0');
            $table->integer('date_update')->length(11)->default('0');
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
        Schema::dropIfExists('language');
    }
}

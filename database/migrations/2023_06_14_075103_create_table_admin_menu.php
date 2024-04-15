<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdminMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menu', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->integer('parentid')->length(11);
            $table->string('g_name', 150);
            $table->string('title_vi', 150)->nullable()->default('NULL');
            $table->string('title_en', 150)->nullable()->default('NULL');
            $table->string('module', 150)->nullable()->default('NULL');
            $table->string('act', 150)->nullable()->default('NULL');
            $table->string('sub',150)->nullable()->default('NULL');
            $table->tinyInteger('is_default')->length(4)->default('0');
            $table->string('menu_icon', 50);
            $table->string('description_vi', 250)->nullable()->default('NULL');
            $table->string('description_en', 250)->nullable()->default('NULL');
            $table->integer('menu_order')->length(11)->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
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
        Schema::dropIfExists('admin_menu');
    }
}

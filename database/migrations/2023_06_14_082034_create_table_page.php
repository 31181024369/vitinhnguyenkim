<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page', function (Blueprint $table) {
            $table->bigIncrements('page_id')->length(20);
            $table->string('picture',150)->nullable()->default('NULL');
            $table->string('template',150)->default('default');
            $table->integer('views')->length(11)->nullable()->default('0');
            $table->tinyInteger('display')->length(4)->nullable()->default('1');
            $table->integer('menu_order')->length(11)->nullable()->default('0');
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
        Schema::dropIfExists('page');
    }
}

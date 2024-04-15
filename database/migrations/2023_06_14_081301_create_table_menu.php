<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('menu_id')->length(11);
            $table->string('target',150)->nullable()->default('_blank');
            $table->integer('parentid')->length(11)->nullable()->default('0');
            $table->string('pos',150);
            $table->string('menu_icon',150)->nullable()->default('NULL');
            $table->text('menu_class')->nullable();
            $table->integer('menu_order')->length(11)->nullable()->default('0');
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
        Schema::dropIfExists('menu');
    }
}

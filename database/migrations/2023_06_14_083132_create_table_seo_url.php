<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSeoUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_url', function (Blueprint $table) {
            $table->bigIncrements('id')->length(32);
            $table->string('name',250);
            $table->string('module',250);
            $table->string('action',250);
            $table->bigInteger('item_id')->length(20);
            $table->string('extra',250)->nullable()->default('NULL');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
            $table->tinyInteger('display')->length(4)->default('1');
            $table->integer('menu_order')->length(11)->default('0');
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
        Schema::dropIfExists('seo_url');
    }
}

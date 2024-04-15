<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->bigIncrements('service_id')->length(20);
            $table->string('picture',250)->nullable()->default('NULL');
            $table->integer('views')->length(11)->nullable()->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
            $table->integer('menu_order')->length(11)->nullable()->default('0');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
            $table->tinyInteger('adminid')->length(4)->default('1');
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
        Schema::dropIfExists('service');
    }
}

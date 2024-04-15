<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRedirect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirect', function (Blueprint $table) {
            $table->increments('redirect_id')->length(11);
            $table->string('old_link');
            $table->string('new_link');
            $table->string('type',50)->default('php');
            $table->integer('menu_order')->length(11)->nullable()->default('0');
            $table->tinyInteger('display')->length(4)->nullable()->default('1');
            $table->string('lang',50)->nullable()->default('vi');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
            $table->integer('adminid')->length(11)->default('1');
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
        Schema::dropIfExists('redirect');
    }
}

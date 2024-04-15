<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMailTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_template', function (Blueprint $table) {
            $table->increments('mailtemp_id')->length(11);
            $table->string('title',150)->default('');
            $table->string('name',150)->default('');
            $table->text('description')->nullable();
            $table->integer('menu_order')->length(11)->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
            $table->string('lang',50)->default('');
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
        Schema::dropIfExists('mail_template');
    }
}

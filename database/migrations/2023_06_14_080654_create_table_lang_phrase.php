<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLangPhrase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lang_phrase', function (Blueprint $table) {
            $table->increments('phrase_id')->length(11);
            $table->string('type',150)->default('modules');
            $table->string('fieldname',150)->nullable()->default('NULL');
            $table->string('title',250)->nullable()->default('NULL');
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
        Schema::dropIfExists('lang_phrase');
    }
}

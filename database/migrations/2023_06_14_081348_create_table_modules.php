<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('name',250);
            $table->string('mod_name',250);
            $table->string('seo_name',150);
            $table->string('seo_name_vi',150);
            $table->tinyInteger('is_seo_link')->length(4)->default('1');
            $table->integer('menu_order')->length(11)->default('0');
            $table->string('mod_icon',150)->default('js/ThemeOffice/module.png');
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
        Schema::dropIfExists('modules');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMailListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_list_table', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->integer('group_id')->length(11)->default('0');
            $table->string('name',150)->nullable()->default('0');
            $table->string('email',150)->nullable()->default('');
            $table->tinyInteger('display')->length(4)->nullable()->default('1');
            $table->integer('menu_order')->length(11)->default('0');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
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
        Schema::dropIfExists('mail_list_table');
    }
}

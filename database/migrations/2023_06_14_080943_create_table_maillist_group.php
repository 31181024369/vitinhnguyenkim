<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMaillistGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maillist_group', function (Blueprint $table) {
            $table->increments('group_id')->length(11);
            $table->string('title',150);
            $table->text('description')->nullable();
            $table->tinyInteger('is_default')->length(4)->default('0');
            $table->tinyInteger('display')->length(4);
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
        Schema::dropIfExists('maillist_group');
    }
}

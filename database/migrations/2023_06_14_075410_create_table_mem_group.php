<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMemGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mem_group', function (Blueprint $table) {
            $table->increments('g_id')->length(11);
            $table->string('g_name', 250);
            $table->text('description')->nullable();
            $table->tinyInteger('is_default')->length(4)->default('0');
            $table->integer('menu_order')->length(10)->nullable()->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
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
        Schema::dropIfExists('mem_group');
    }
}

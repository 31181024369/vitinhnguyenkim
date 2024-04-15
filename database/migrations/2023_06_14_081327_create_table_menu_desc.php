<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMenuDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_desc', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->unsignedInteger('menu_id')->length(11);
            $table->foreign('menu_id')
            ->references('menu_id')
            ->on('menu')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('name',150)->nullable()->default('NULL');
            $table->string('title',250);
            $table->string('link',250)->nullable()->default('NULL');
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
        Schema::dropIfExists('menu_desc');
    }
}

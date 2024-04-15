<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePageDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_desc', function (Blueprint $table) {
            $table->bigIncrements('id')->length(32);
            $table->unsignedBigInteger('page_id')->length(20);
            $table->foreign('page_id')
            ->references('page_id')
            ->on('page')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title',150)->default('');
            $table->text('description');
            $table->string('friendly_url',250);
            $table->string('friendly_title',250);
            $table->string('metakey',250);
            $table->text('metadesc');
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
        Schema::dropIfExists('page_desc');
    }
}

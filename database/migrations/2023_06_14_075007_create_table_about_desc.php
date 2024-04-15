<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAboutDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_desc', function (Blueprint $table) {
            $table->bigIncrements('id')->length(20);
            $table->unsignedBigInteger('about_id')->length(20);
            $table->foreign('about_id')
            ->references('about_id')
            ->on('about')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title', 250);
            $table->longText('description');
            $table->string('friendly_url', 250);
            $table->string('friendly_title', 250);
            $table->string('metakey',250);
            $table->text('metadesc');
            $table->string('lang', 250)->default('vi');
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
        Schema::dropIfExists('about_desc');
    }
}

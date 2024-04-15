<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFaqsDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs_desc', function (Blueprint $table) {
            $table->bigIncrements('id')->length(20);
            $table->bigInteger('faqs_id')->length(20)->unsigned();
            $table->foreign('faqs_id')
            ->references('faqs_id')
            ->on('faqs')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title',250);
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('faqs_desc');
    }
}

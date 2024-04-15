<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStatisticsVisitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_visitor', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->date('last_counter');
            $table->text('referred');
            $table->string('agent', 255);
            $table->string('platform', 255)->nullable()->default('NULL');
            $table->string('version', 255)->nullable()->default('NULL');
            $table->string('UAString', 255)->nullable()->default('NULL');
            $table->string('ip',60);
            $table->string('location',10)->nullable()->default('NULL');
            $table->integer('hits')->length(11)->nullable()->default('0');
            $table->integer('honeypot')->length(11)->nullable()->default('0');
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
        Schema::dropIfExists('statistics_visitor');
    }
}

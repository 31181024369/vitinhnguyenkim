<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStatisticsUseronline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_useronline', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('ip',60);
            $table->integer('created')->length(11)->nullable()->default('0');
            $table->integer('timestamp')->length(10);
            $table->datetime('date');
            $table->text('referred');
            $table->string('agent',255);
            $table->string('platform',255)->nullable()->default('NULL');
            $table->string('version',255)->nullable()->default('NULL');
            $table->string('location',10)->nullable()->default('NULL');
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
        Schema::dropIfExists('statistics_useronline');
    }
}

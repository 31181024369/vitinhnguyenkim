<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStatisticsSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_setting', function (Blueprint $table) {
            $table->bigIncrements('id')->length(20);
            $table->tinyInteger('use_statistics')->length(4);
            $table->integer('user_online')->length(11)->default('0');
            $table->integer('visit_default')->length(11)->default('0');
            $table->tinyInteger('useronline')->length(4)->default('1');
            $table->integer('check_online')->length(11)->default('30');
            $table->tinyInteger('visits')->length(4)->default('1');
            $table->tinyInteger('visitors')->length(4)->default('1');
            $table->integer('coefficient')->length(11)->default('1');
            $table->tinyInteger('pages')->length(4)->default('1');
            $table->tinyInteger('record_exclusions')->length(4)->default('0');
            $table->text('robotlist')->nullable();
            $table->text('exclude_ip')->nullable();
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
        Schema::dropIfExists('statistics_setting');
    }
}

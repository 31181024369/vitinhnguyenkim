<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStatisticsExclusions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_exclusions', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->date('date');
            $table->string('reason',255)->nullable()->default('NULL');
            $table->bigInteger('count')->length(20);
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
        Schema::dropIfExists('statistics_exclusions');
    }
}

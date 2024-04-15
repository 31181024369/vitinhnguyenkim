<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStatisticsPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_pages', function (Blueprint $table) {
            $table->string('uri');
            $table->date('date');
            $table->integer('count')->length(11)->default('0');
            $table->integer('id')->length(11)->default('0');
            $table->string('module',250)->nullable()->default('NULL');
            $table->string('action',250)->nullable()->default('NULL');
            $table->string('friendly_url',250)->nullable()->default('NULL');
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
        Schema::dropIfExists('statistics_pages');
    }
}

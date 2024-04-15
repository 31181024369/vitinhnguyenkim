<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdminlogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adminlogs', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->unsignedInteger('adminid')->length(11);
            $table->foreign('adminid')
            ->references('adminid')
            ->on('admin')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->integer('time')->length(11)->default('0');
            $table->string('ip', 20)->default('');
            $table->string('action', 50)->nullable()->default('NULL');
            $table->string('cat', 250)->nullable()->default('NULL');
            $table->string('pid', 250)->nullable()->default('NULL');
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
        Schema::dropIfExists('adminlogs');
    }
}

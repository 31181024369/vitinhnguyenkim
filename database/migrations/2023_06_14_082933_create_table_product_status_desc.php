<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductStatusDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_status_desc', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->unsignedInteger('status_id')->length(11);
            $table->foreign('status_id')
            ->references('status_id')
            ->on('product_status')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title',150);
            $table->text('description')->nullable();
            $table->string('friendly_url',250);
            $table->string('friendly_title',250)->nullable()->default('NULL');
            $table->string('metakey',250)->nullable()->default('NULL');
            $table->string('metadesc',250)->nullable()->default('NULL');
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
        Schema::dropIfExists('product_status_desc');
    }
}

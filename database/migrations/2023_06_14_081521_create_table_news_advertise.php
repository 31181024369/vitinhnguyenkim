<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNewsAdvertise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_advertise', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('title',150);
            $table->string('type',50)->nullable()->default('');
            $table->string('pos',50)->nullable()->default('NULL');
            $table->string('width',10)->nullable()->default('0');
            $table->integer('itemID')->length(11)->default('0');
            $table->string('picture',250)->nullable()->default('NULL');
            $table->string('link',150)->nullable()->default('NULL');
            $table->string('target',50)->default('_blank');
            $table->string('height',10)->nullable()->default('0');
            $table->text('description')->nullable();
            $table->integer('menu_order',)->length(11)->default('0');
            $table->tinyInteger('display')->length(4)->default('1');
            $table->string('lang',50)->default('vi');
            $table->integer('date_post')->length(11);
            $table->integer('date_update')->length(11);
            $table->integer('adminid')->length(11);
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
        Schema::dropIfExists('news_advertise');
    }
}

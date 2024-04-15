<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableContactContactQoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_qoute', function (Blueprint $table) {
            $table->increments('id')->length(10);
            $table->string('name', 250)->nullable()->default('NULL');
            $table->string('phone', 150);
            $table->string('email', 150)->nullable()->default('NULL');
            $table->string('company', 250)->nullable()->default('NULL');
            $table->string('address', 250)->nullable()->default('NULL');
            $table->text('content');
            $table->string('attach_file', 150)->nullable()->default('NULL');
            $table->tinyInteger('status')->length(3)->default('0');
            $table->integer('menu_order')->length(10)->default('0');
            $table->string('date_post', 150);
            $table->integer('date_update')->length(10);
            $table->string('lang', 50)->nullable()->default('vi');
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
        Schema::dropIfExists('contact_qoute');
    }
}

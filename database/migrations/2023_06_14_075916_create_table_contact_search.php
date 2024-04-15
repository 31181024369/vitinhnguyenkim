<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableContactSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_search', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('phone', 150);
            $table->string('email', 150)->nullable()->default('NULL');
            $table->text('options')->nullable();
            $table->tinyInteger('status')->length(4)->default('0');
            $table->string('ip_address', 50);
            $table->string('date_post', 150);
            $table->integer('date_update')->length(11);
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
        Schema::dropIfExists('contact_search');
    }
}

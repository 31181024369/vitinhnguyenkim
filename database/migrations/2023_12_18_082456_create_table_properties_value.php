<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePropertiesValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_value', function (Blueprint $table) {
            $table->bigIncrements('id')->length(20);
            $table->bigIncrements('properties_id')->length(20);
            $table->foreign('properties_id')
            ->references('properties_id')
            ->on('categoryproperties_properties')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('name',255);
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
        Schema::dropIfExists('properties_value');
    }
}

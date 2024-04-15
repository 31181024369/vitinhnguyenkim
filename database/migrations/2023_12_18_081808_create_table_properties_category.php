<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePropertiesCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_category', function (Blueprint $table) {
            $table->bigIncrements('id')->length(20);
            $table->unsignedInteger('cat_id')->length(10);
            $table->foreign('cat_id')
            ->references('cat_id')
            ->on('price_category')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->bigIncrements('properties_id')->length(20);
            $table->foreign('properties_id')
            ->references('properties_id')
            ->on('categoryproperties_properties')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('properties_category');
    }
}

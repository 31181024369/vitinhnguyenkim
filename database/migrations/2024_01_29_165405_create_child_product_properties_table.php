<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildProductPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_product_properties', function (Blueprint $table) {
            $table->bigIncrements('id')->length(20);
            $table->bigIncrements('pv_id')->length(20);
            $table->bigIncrements('cat_id')->length(20);
            $table->bigIncrements('properties_id')->length(20);
            $table->bigIncrements('price_id')->length(20);
            $table->longText('description',255);
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
        Schema::dropIfExists('child_product_properties');
    }
}

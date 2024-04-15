<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductBrandDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_brand_desc', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->unsignedInteger('brand_id')->length(11);
            $table->foreign('brand_id')
            ->references('brand_id')
            ->on('product_brand')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title',250);
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
        Schema::dropIfExists('product_brand_desc');
    }
}

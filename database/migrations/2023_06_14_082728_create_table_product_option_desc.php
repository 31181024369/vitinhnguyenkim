<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductOptionDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_desc', function (Blueprint $table) {
            $table->integer('id',11);
            $table->unsignedInteger('option_id')->length(11);
            $table->foreign('option_id')
            ->references('option_id')
            ->on('product_option')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('title',150);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('product_option_desc');
    }
}

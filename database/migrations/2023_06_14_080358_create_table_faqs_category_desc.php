<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFaqsCategoryDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs_category_desc', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->unsignedInteger('cat_id')->length(11);
            $table->foreign('cat_id')
            ->references('cat_id')
            ->on('faqs_category')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('cat_name',250);
            $table->longText('description')->nullable();
            $table->string('friendly_url',250);
            $table->string('friendly_title',250)->nullable()->default('NULL');
            $table->string('metakey',250)->nullable()->default('NULL');
            $table->text('metadesc')->nullable();
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
        Schema::dropIfExists('faqs_category_desc');
    }
}

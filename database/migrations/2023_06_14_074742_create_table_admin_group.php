<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdminGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_group', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('title', 150)->default('');
            $table->string('role', 150)->nullable()->default('NULL');
            $table->text('permission')->nullable();
            $table->tinyInteger('is_default')->length(4)->default('0');
            $table->integer('menu_order')->length(11)->default('0');
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
        Schema::dropIfExists('admin_group');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('adminid')->length(11);
            $table->string('username', 50);
            $table->string('password', 50);
            $table->string('email', 100);
            $table->unsignedInteger('level')->length(11);
            $table->foreign('level')
            ->references('id')
            ->on('admin_group')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('display_name', 250);
            $table->string('avatar', 250)->nullable()->default('NULL');
            $table->string('skin',250)->default('blue');
            $table->tinyInteger('is_default')->length(4)->default('0');
            $table->string('lastlogin', 150)->default('0');
            $table->string('code_reset', 150)->nullable()->default('NULL');
            $table->integer('menu_order')->length(11)->default('0');
            $table->integer('phone')->length(11)->nullable()->default('0');
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
        Schema::dropIfExists('admin');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->length(11);
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unsignedInteger('coupon_id')->length(11);
            $table->foreign('coupon_id')
                    ->references('id')
                    ->on('coupon')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unsignedInteger('product_id')->length(11);
            $table->foreign('product_id')
                    ->references('product_id')
                    ->on('product')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unsignedInteger('mem_id')->length(11);
            $table->foreign('mem_id')
                    ->references('mem_id')
                    ->on('members')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->text('description');
            $table->string('action');
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
        Schema::dropIfExists('log');
    }
}

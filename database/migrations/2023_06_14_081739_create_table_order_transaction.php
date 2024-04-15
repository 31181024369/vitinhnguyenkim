<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transaction', function (Blueprint $table) {
            $table->bigIncrements('ID')->length(20);
            $table->string('transaction_code', 255);
            $table->string('order_code', 255);
            $table->foreign('order_code')
            ->references('order_code')
            ->on('order_sum')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('order_type',50)->nullable()->default('cart');
            $table->longText('order_info')->nullable();
            $table->string('order_note')->nullable()->default('');
            $table->string('create_date',50)->nullable()->default('');
            $table->integer('create_time')->length(11)->nullable()->default('0');
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
        Schema::dropIfExists('order_transaction');
    }
}

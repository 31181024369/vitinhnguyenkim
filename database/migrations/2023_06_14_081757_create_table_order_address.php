<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_address', function (Blueprint $table) {
            $table->increments('id')->length(11);
            $table->string('session',200);
            $table->integer('mem_id')->length(11)->default('0');
            $table->string('d_name',150)->nullable()->default('NULL');
            $table->string('d_address',150)->nullable()->default('');
            $table->string('d_email',150)->nullable()->default('');
            $table->string('d_phone',150)->default('');
            $table->string('c_name',150)->default('');
            $table->string('c_address',150)->default('');
            $table->string('c_phone',150)->default('');
            $table->string('c_email',250)->nullable()->default('NULL');
            $table->integer('s_price')->length(11)->nullable()->default('0');
            $table->text('comment')->nullable();
            $table->string('payment_method',200)->nullable()->default('NULL');
            $table->string('shipping_name',250)->nullable()->default('NULL');
            $table->string('shipping_method',200)->nullable()->default('NULL');
            $table->string('payment_name',250)->nullable()->default('NULL');
            $table->integer('date_post')->length(11);
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
        Schema::dropIfExists('order_address');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id')->length(11);
            $table  ->foreign('product_id')
                    ->references('product_id')
                    ->on('product')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('nameCombo');
            $table->string('priceCombo')->nullable();
            $table->text('description');
            $table->string('image');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('combo_products');
    }
}

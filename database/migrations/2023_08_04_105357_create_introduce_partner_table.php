<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntroducePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('introduce_partner', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('phone',255);
            $table->string('company',255);
            $table->string('buyer',255);
            $table->string('phonebuyer',255);
            $table->string('companybuyer',255);
            $table->string('order',255);
            $table->string('quanlity',255);
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
        Schema::dropIfExists('introduce_partner');
    }
}

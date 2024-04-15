<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('mem_id')->length(11);
            $table->unsignedInteger('mem_group')->length(10)->default('0');
            $table->foreign('mem_group')
            ->references('g_id')
            ->on('mem_group')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('username', 150)->default('');
            $table->string('mem_code', 150);
            $table->string('email', 200)->nullable()->default('NULL');
            $table->string('password', 100)->default('');
            $table->string('activate_code', 100)->default('');
            $table->string('address', 200)->nullable()->default('NULL');
            $table->string('company', 250)->nullable()->default('NULL');
            $table->string('full_name', 250);
            $table->string('gender', 40)->default('0');
            $table->string('birthday', 150)->nullable()->default('NULL');
            $table->string('avatar', 255)->nullable()->default('NULL');
            $table->string('phone', 50)->nullable()->default('NULL');
            $table->text('buildpc')->nullable();
            $table->tinyInteger('newsletter')->length(4)->default('1');
            $table->integer('date_join')->length(10)->default('0');
            $table->integer('last_login')->length(10)->default('0');
            $table->tinyInteger('m_status')->length(3)->default('0');
            $table->integer('mem_point')->length(10)->default('0');
            $table->integer('mem_point_use')->length(10)->default('0');
            $table->string('api_type', 255)->nullable()->default('NULL');
            $table->string('api_user', 255)->nullable()->default('NULL');
            $table->string('api_pass', 255)->nullable()->default('NULL');
            $table->integer('menu_order')->length(11)->default(0);
            $table->string('Tencongty', 250)->nullable()->default('NULL');
            $table->string('Masothue', 250)->nullable()->default('NULL');
            $table->string('Diachicongty', 250)->nullable()->default('NULL');
            $table->string('Sdtcongty', 250)->nullable()->default('NULL');
            $table->string('emailcty', 250)->nullable()->default('NULL');
            $table->string('idmacoupon', 2500);
            $table->string('MaKH', 250);
            $table->string('MaKHDinhDanh', 255);
            $table->string('district', 200)->nullable()->default('NULL');
            $table->string('city_province', 200)->nullable()->default('NULL');
            $table->integer('status')->defaut(0);
            $table->rememberToken();
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
        Schema::dropIfExists('members');
    }
}

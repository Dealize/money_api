<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('sex')->nullable();
            $table->integer('age')->nullable();
            $table->string('headpic')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
//因为这三个表是 Mylsam格式  不是innoDB格式，所以就不用外键了（不支持外键）；clear
//            $table->foreign('area_id')->references('areaID')->on('hat_area');
//            $table->foreign('city_id')->references('cityId')->on('hat_city');
//            $table->foreign('province_id')->references('provinceId')->on('hat_province');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::drop('users');
    }
}

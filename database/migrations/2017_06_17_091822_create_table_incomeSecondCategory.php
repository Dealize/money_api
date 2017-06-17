<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIncomeSecondCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomeSecondCategory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('firstCategory_id');
            $table->unsignedInteger('user_id')->nullable()->default(0);
            $table->string('valiable')->default('true');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('firstCategory_id')->references('id')->on('incomeCategory');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomeSecondCategory');
    }
}

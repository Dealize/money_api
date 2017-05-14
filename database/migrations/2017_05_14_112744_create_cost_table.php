<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('dailyCost',7,2)->comment('日均消费');
            $table->unsignedInteger('bill_id');
            $table->timestamp('endTime');
            $table->string('isWorth')->comment('是否值得');
            $table->unsignedInteger('user_id');
            $table->integer('days')->comment('持续的天数');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bill_id')->references('id')->on('bills');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost');
    }
}

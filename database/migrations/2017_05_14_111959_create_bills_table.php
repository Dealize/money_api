<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('money',7,2);
            $table->string('billType')->comment('收入、支出');
            $table->string('isWorth')->comment('是否值得');
            $table->text('comment')->nullable();
            $table->dateTime('beginTime');
            $table->dateTime('endTime');
            $table->integer('days')->comment('持续的天数')->default(1);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('wallet_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('secondCategory');
            $table->foreign('wallet_id')->references('id')->on('wallet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}

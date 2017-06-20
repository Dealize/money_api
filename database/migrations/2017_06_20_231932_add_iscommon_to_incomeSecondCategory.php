<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIscommonToIncomeSecondCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incomeSecondCategory', function (Blueprint $table) {
            //
            $table->string('commonType')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incomeSecondCategory', function (Blueprint $table) {
            //
            $table->dropColumn('commonType');

        });
    }
}

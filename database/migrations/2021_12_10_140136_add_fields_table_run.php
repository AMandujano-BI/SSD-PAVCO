<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTableRun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('runs', function (Blueprint $table) {
            $table->boolean('isEdit');
            $table->boolean('isReport');
            $table->dateTime('lastDateEdit')->nullable();
            $table->integer('hours')->default(0);
            $table->dateTime('closed_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('runs', function (Blueprint $table) {
            $table->dropColumn('isEdit');
            $table->dropColumn('isReport');
            $table->dropColumn('lastDateEdit')->nullable();
            $table->dropColumn('hours')->default(0);
            $table->dropColumn('closed_date')->nullable();
        });
    }
}

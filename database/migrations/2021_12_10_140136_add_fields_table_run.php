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
            $table->boolean('isEdit')->default(0);
            $table->boolean('isReport')->default(0);
            $table->timestamp('lastDateEdit')->nullable();
            $table->integer('hours')->default(0);
            $table->timestamp('closed_date')->nullable();
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

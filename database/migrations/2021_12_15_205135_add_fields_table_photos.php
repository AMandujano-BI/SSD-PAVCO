<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTablePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('photos', function (Blueprint $table) {
            $table->boolean('isEdit')->default(0);
            $table->boolean('isReport')->default(0);
            $table->timestamp('last_edit')->nullable();
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
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('isEdit');
            $table->dropColumn('isReport');
            $table->dropColumn('last_edit')->nullable();
            $table->dropColumn('closed_date')->nullable();
        });
    }
}

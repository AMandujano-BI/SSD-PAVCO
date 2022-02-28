<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('runs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number');
            $table->dateTime('start_date');
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('dateCompleted')->nullable();


            //-------------------------------
            $table->string('plateThick', 50)->nullable();
            $table->foreignId('plate_methods_id')->constrained();

            $table->foreignId('company_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();

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
        Schema::dropIfExists('runs');
    }
}

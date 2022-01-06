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
            $table->text('description');
            $table->boolean('status')->default(0);
            $table->dateTime('dateCompleted')->nullable();

          

            //-------------------------------
            $table->string('plateThick', 50)->nullable();
            $table->string('primaryPer', 50)->nullable();
            $table->string('coatPer', 50)->nullable();
            $table->string('topCoatPer', 50)->nullable();
            //$table->string('plateMethod',50);
            $table->foreignId('plate_methods_id')->constrained();

            // $table->integer('company_id')->default(1);
            $table->foreignId('company_id')->nullable()->constrained();
            // $table->integer('user_id')->default(1);
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

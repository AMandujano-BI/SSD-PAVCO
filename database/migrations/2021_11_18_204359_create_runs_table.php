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
            $table->integer('number');
            $table->dateTime('startDate');
            $table->text('description');
            $table->boolean('status')->default(1);
            $table->dateTime('dateCompleted');


            // $table->string('plate',50);
            $table->foreignId('plate_types_id')->constrained();

            //$table->string('primaryCoat',50);
            $table->unsignedBigInteger('primaryCoatId');
            $table->foreign('primaryCoatId')->references('id')->on('chemicals');

            // $table->string('coat', 50);
            $table->unsignedBigInteger('coatId');
            $table->foreign('coatId')->references('id')->on('chemicals');

            // $table->string('topCoat', 50);
            $table->unsignedBigInteger('topCoatId');
            $table->foreign('topCoatId')->references('id')->on('chemicals');

            //-------------------------------
            $table->string('plateThick', 50);
            $table->string('primaryPer', 50);
            $table->string('coatPer', 50);
            $table->string('topCoatPer', 50);
            //$table->string('plateMethod',50);
            $table->foreignId('plate_methods_id')->constrained();

            $table->integer('company_id')->default(1);
            // $table->foreignId('company_id')->constrained();
            $table->integer('user_id')->default(1);
            // $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }
    //Parts
    // $table->id();
    // $table->string('plate',50);
    // $table->string('primaryCoat',50);
    // $table->string('coat',50);
    // $table->string('plateThick',50);
    // $table->string('primaryPer');
    // $table->string('coatPer');
    // $table->string('description');
    // $table->string('topCoat');
    // $table->string('topCoatPer');
    // $table->string('topCoatTemp');
    // $table->string('topCoatPH');
    // $table->string('topCoatDiptime');
    // $table->string('primaryTemp');
    // $table->string('primaryPH');
    // $table->string('primaryDiptime');
    // $table->string('coatTemp');
    // $table->string('coatPH');
    // $table->string('coatDiptime');
    // $table->foreignId('run_id')->constrained();
    // $table->timestamps();
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

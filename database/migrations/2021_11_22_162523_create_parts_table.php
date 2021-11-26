<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('plateThick', 50);
            $table->string('description', 50);
            $table->string('topCoatPer', 50);
            $table->string('topCoatTemp', 50);
            $table->string('topCoatPH', 50);
            $table->string('topCoatDiptime', 50);
            $table->string('primaryPer', 50);
            $table->string('primaryTemp', 50);
            $table->string('primaryPH', 50);
            $table->string('primaryDiptime', 50);
            $table->string('coatPer', 50);
            $table->string('coatTemp', 50);
            $table->string('coatPH', 50);
            $table->string('coatDiptime', 50);
            // Relations
            $table->unsignedBigInteger('plate_types_id');
            $table->foreign('plate_types_id')->references('id')->on('chemicals');
            $table->unsignedBigInteger('primaryCoatId');
            $table->foreign('primaryCoatId')->references('id')->on('chemicals');
            $table->unsignedBigInteger('coatId');
            $table->foreign('coatId')->references('id')->on('chemicals');
            $table->unsignedBigInteger('topCoatId');
            $table->foreign('topCoatId')->references('id')->on('chemicals');
            $table->foreignId('run_id')->constrained();
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
        Schema::dropIfExists('parts');
    }
}

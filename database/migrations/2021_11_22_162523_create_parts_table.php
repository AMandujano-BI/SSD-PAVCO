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
            $table->string('description', 50)->nullable();
            $table->string('topCoatPer', 50)->nullable();;
            $table->string('topCoatTemp', 50)->nullable();;
            $table->string('topCoatPH', 50)->nullable();;
            $table->string('topCoatDiptime', 50)->nullable();;
            $table->string('primaryPer', 50)->nullable();;
            $table->string('primaryTemp', 50)->nullable();;
            $table->string('primaryPH', 50)->nullable();;
            $table->string('primaryDiptime', 50)->nullable();;
            $table->string('coatPer', 50)->nullable();;
            $table->string('coatTemp', 50)->nullable();;
            $table->string('coatPH', 50)->nullable();;
            $table->string('coatDiptime', 50)->nullable();;
            // Relations
            $table->foreignId('plate_types_id')->nullable()->constrained('chemicals');
            $table->foreignId('primaryCoatId')->nullable()->constrained('chemicals');
            $table->foreignId('coatId')->nullable()->constrained('chemicals');
            $table->foreignId('topCoatId')->nullable()->constrained('chemicals');
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

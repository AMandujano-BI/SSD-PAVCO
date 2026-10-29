<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePartPlateLayersTable extends Migration
{
    public function up()
    {
        Schema::create('part_plate_layers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();
            $table->unsignedSmallInteger('position');
            $table->foreignId('plate_type_id')->constrained('chemicals');
            $table->string('thickness', 50)->nullable();
            $table->string('thickness_unit', 50)->nullable();
            $table->string('per', 50)->nullable();
            $table->string('temp', 50)->nullable();
            $table->string('ph', 50)->nullable();
            $table->string('diptime', 50)->nullable();
            $table->timestamps();

            $table->unique(['part_id', 'position']);
        });

        DB::table('parts')
            ->whereNotNull('plate_types_id')
            ->orderBy('id')
            ->chunkById(500, function ($parts) {
                $now = now();
                DB::table('part_plate_layers')->insert(
                    $parts->map(function ($part) use ($now) {
                        return [
                            'part_id' => $part->id,
                            'position' => 1,
                            'plate_type_id' => $part->plate_types_id,
                            'thickness' => $part->plateThick,
                            'thickness_unit' => $part->typePlateThick,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })->all()
                );
            });
    }

    public function down()
    {
        Schema::dropIfExists('part_plate_layers');
    }
}

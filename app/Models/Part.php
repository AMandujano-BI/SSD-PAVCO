<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Part extends Model
{
    use HasFactory;
    protected $fillable = [
        'plateThick',
        'description',
        'topCoatPer',
        'topCoatTemp',
        'topCoatPH',
        'topCoatDiptime',
        'primaryPer',
        'primaryTemp',
        'primaryPH',
        'primaryDiptime',
        'coatPer',
        'coatTemp',
        'coatPH',
        'coatDiptime',
        'run_id',
    ];

    public static function getPartsByRun($id)
    {
        $parts = (new static)::with([
            'notes'
        ])->where('run_id', $id)->get();
        return $parts;
    }
    public static function createPart($request)
    {
        DB::beginTransaction();
        try {
            $plateThick = $request->startDate;
            $primaryPer = $request->description;
            $coatPer = $request->plate_types_id;
            $description = $request->primaryCoatId;
            $topCoatPer = $request->coatId;
            $topCoatPH = $request->topCoatId;
            $topCoatDiptime = $request->plate_methods_id;
            $primaryTemp = $request->plate_methods_id;
            $primaryPH = $request->plate_methods_id;
            $primaryDiptime = $request->plate_methods_id;
            $coatPH = $request->plate_methods_id;
            $coatDiptime = $request->plate_methods_id;
            $run_id = $request->plate_methods_id;

            $part = (new static)::create([
                'plateThick' => $plateThick,
                'primaryPer' => $primaryPer,
                'coatPer' => $coatPer,
                'description' => $description,
                'topCoatPer' => $topCoatPer,
                'topCoatPH' => $topCoatPH,
                'topCoatDiptime' => $topCoatDiptime,
                'primaryTemp' => $primaryTemp,
                'primaryPH' => $primaryPH,
                'primaryDiptime' => $primaryDiptime,
                'coatPH' => $coatPH,
                'coatDiptime' => $coatDiptime,
                'rund_id' => $run_id,
            ]);
            $part->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Part was created successfully'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function updatePart($id, $request)
    {
        DB::beginTransaction();
        try {
            $part = (new static)::find($id);
            $part->description = $request->description;
            $part->topCoatPer = $request->topCoatPer;
            $part->topCoatTemp = $request->topCoatTemp;
            $part->topCoatPH = $request->topCoatPH;
            $part->topCoatDiptime = $request->topCoatDiptime;
            $part->primaryPer = $request->primaryPer;
            $part->primaryTemp = $request->primaryTemp;
            $part->primaryPH = $request->primaryPH;
            $part->primaryDiptime = $request->primaryDiptime;
            $part->coatPer = $request->coatPer;
            $part->coatTemp = $request->coatTemp;
            $part->coatPH = $request->coatPH;
            $part->coatDiptime = $request->coatDiptime;
            $part->save();
            $run = Run::find($request->run_id);
            if ($run == null) {

                DB::rollBack();
                return [
                    'ok' => false,
                    'message' => 'Run not found',
                    'value' => 0,
                ];
            }
            $run->plate_types_id = $request->plate_types_id;
            $run->plateThick= $request->plateThick;
            $run->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Part was update successfully',
                'value' => $part,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function deletePart($id)
    {
        $partDeleted = (new static)::where('id', $id)->delete();
        return [
            'ok' => true,
            'message' => 'Part was deleted successfully',
            'value' => $partDeleted,
        ];
    }


    // ===================================RELATIONS
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}

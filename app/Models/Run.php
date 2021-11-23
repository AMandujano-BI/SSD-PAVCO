<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Run extends Model
{
    use HasFactory;
    protected $fillable = [
        'startDate',
        'number',
        'description',
        'plate_types_id',
        'primaryCoatId',
        'coatId',
        'topCoatId',
        'coatPer',
        'plateThick',
        'plate_methods_id',
        'company_id',
        'user_id',
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
    ];


    public static function getAllRun()
    {
        $run = (new static)::all();
        return $run;
    }

    public static function createRun($request)
    {
        DB::beginTransaction();
        try {

            $startDate = $request->startDate;
            $description = $request->description;
            $plate_types_id = $request->plate_types_id;
            $primaryCoatId = $request->primaryCoatId;
            $coatId = $request->coatId;
            $topCoatId = $request->topCoatId;
            $plate_methods_id = $request->plate_methods_id;
            $numberParts = $request->numberParts;

            $run = (new static)::create([
                'startDate' => $startDate,
                'number' => 1,
                'description' => $description,
                'plate_types_id' => $plate_types_id,
                'primaryCoatId' => $primaryCoatId,
                'coatId' => $coatId,
                'topCoatId' => $topCoatId,
                'plateThick' => 0,
                'primaryPer' => 0,
                'coatPer' => 0,
                'topCoatPer' => 0,
                'plate_methods_id' => $plate_methods_id,
                'company_id' => 1,
                'user_id' => 1,
            ]);
            $run->save();


            $plateThick = $request->plateThick;
            $topCoatPer = $request->topCoatPer;
            $topCoatTemp = $request->topCoatTemp;
            $topCoatPH = $request->topCoatPH;
            $topCoatDiptime = $request->topCoatDiptime;
            $primaryPer= $request->primaryPer;
            $primaryTemp = $request->primaryTemp;
            $primaryPH = $request->primaryPH;
            $primaryDiptime = $request->primaryDiptime;
            $coatPer= $request->coatPer;
            $coatTemp = $request->coatTemp;
            $coatPH = $request->coatPH;
            $coatDiptime = $request->coatDiptime;

            for ($i = 0; $i < $numberParts; $i++) {
                $parts = Part::create([
                    'plateThick' => $plateThick,
                    'description' => 'is a description',
                    'topCoatPer' => $topCoatPer,
                    'topCoatTemp' => $topCoatTemp,
                    'topCoatPH' => $topCoatPH,
                    'topCoatDiptime' => $topCoatDiptime,
                    'primaryPer' => $primaryPer,
                    'primaryTemp' => $primaryTemp,
                    'primaryPH' => $primaryPH,
                    'primaryDiptime' => $primaryDiptime,
                    'coatPer' => $coatPer,
                    'coatTemp' => $coatTemp,
                    'coatPH' => $coatPH,
                    'coatDiptime' => $coatDiptime,
                    'run_id' => $run->id,
                ]);
                $parts->save();
            }
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Run was created successfully',
                'value' => $run,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
            // return $e->getMessage();
        }
    }
}

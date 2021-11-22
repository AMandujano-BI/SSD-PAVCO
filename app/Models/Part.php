<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Part extends Model
{
    use HasFactory;
    protected $fillable =[
        'plateThick',
        'primaryPer',
        'coatPer',
        'description',
        'topCoatPer',
        'topCoatTemp',
        'topCoatPH',
        'topCoatDiptime',
        'primaryTemp',
        'primaryPH',
        'primaryDiptime',
        'coatPH',
        'coatDiptime',
        'run_id',
    ];
    public static function createPart($request)
    {
        DB::beginTransaction();
        try {
            $plateThick= $request->startDate;
            $primaryPer = $request->description;
            $coatPer = $request->plate_types_id;
            $description = $request->primaryCoatId;
            $topCoatPer = $request->coatId;
            $topCoatPH = $request->topCoatId;
            $topCoatDiptime = $request->plate_methods_id;
            $primaryTemp= $request->plate_methods_id;
            $primaryPH = $request->plate_methods_id;
            $primaryDiptime = $request->plate_methods_id;
            $coatPH= $request->plate_methods_id;
            $coatDiptime= $request->plate_methods_id;
            $run_id= $request->plate_methods_id;

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
}

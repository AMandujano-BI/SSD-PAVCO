<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Run extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'startDate',
        'number',
        'plate_types_id'
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
            $description = $request->startDate;
            $plate_types_id = $request->plate_types_id;
            $primaryCoatId = $request->primaryCoatId;
            $coatId = $request->coatId;
            $topCoatId = $request->topCoatId;
            $plateMethod = $request->plateMethod;

            $run = (new static)::create([
                'startDate' => $startDate,
                'number'=>1,
                'description' => $description,
                'plate_types_id' => $plate_types_id,
                'primaryCoatid' => $primaryCoatId,
                'coatId' => $coatId,
                'topCoatId' => $topCoatId,
                'plateThick' => 0,
                'primaryPer' => 0,
                'coatPer' => 0,
                'topCoatPer' => 0,
                'plate_methods_id' => $plateMethod,
                'company_id' => 1,
                'user_id' => 1,
            ]);
            $run->save();
            DB::commit();
            return 'okar';
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}

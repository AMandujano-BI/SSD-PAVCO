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
        'typePlateThick',
        'description',
        'plate_types_id',
        'primaryCoatId',
        'coatId',
        'topCoatId',
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
        'created_at',
        'number'
    ];

    public static function getPartsByRun($id)
    {
        $parts = (new static)::with([
            'notes',
            'topCoat',
            'chromate',
            'coat',
            'plateType',
        ])->where('run_id', $id)->orderBy('number', 'asc')->get();
        return $parts;
    }
    public static function getNextNumberDescription($idRun)
    {

        $number = 0;

        $getLastNumber = (new static)
            ->where('number', DB::raw("(select max(`number`) from parts where run_id = $idRun)"))
            ->first();
        if ($getLastNumber == null) {
            return 0;
        }
        $number = $getLastNumber->number + 1;

        return $number;
    }


    public static function createPart($request)
    {
        DB::beginTransaction();
        try {
            $plateThick = $request->plateThick;
            $typePlateThick = $request->typePlateThick;
            $plate_types_id = $request->plate_types_id;
            $primaryCoatId = $request->primaryCoatId;
            $coatId = $request->coatId;
            $description = $request->description;
            $topCoatPer = $request->topCoatPer;
            $topCoatTemp = $request->topCoatTemp;
            $topCoatPH = $request->topCoatPH;
            $topCoatDiptime = $request->topCoatDiptime;
            $primaryPer = $request->primaryPer;
            $primaryTemp = $request->primaryTemp;
            $primaryPH = $request->primaryPH;
            $primaryDiptime = $request->primaryDiptime;
            $coatPer = $request->coatPer;
            $coatTemp = $request->coatTemp;
            $coatPH = $request->coatPH;
            $coatDiptime = $request->coatDiptime;
            $run_id = $request->run_id;
            $topCoatId = $request->topCoatId;


            if ($plate_types_id == '' || $plate_types_id == 0) $plate_types_id = null;
            if ($primaryCoatId == '' || $primaryCoatId == 0) $primaryCoatId = null;
            if ($coatId == '' || $coatId == 0) $coatId = null;
            if ($topCoatId == '' || $topCoatId == 0) $topCoatId = null;
            if ($plate_types_id == '' || $plate_types_id == 0) $plate_types_id = null;


            if ($primaryCoatId == null) {
                $primaryPer = null;
                $primaryPH = null;
                $primaryDiptime = null;
                $primaryTemp = null;
            }
            if ($coatId == null) {
                $coatPer = null;
                $coatPH = null;
                $coatDiptime = null;
                $coatTemp = null;
            }
            if ($topCoatId == null) {
                $topCoatPH = null;
                $topCoatTemp = null;
                $topCoatDiptime = null;
                $topCoatPer = null;
            }
            $number = 0;

            $getLastNumber = (new static)
                ->where('run_id', $run_id)
                ->where('number', DB::raw("(select max(`number`) from parts)"))->first();
            $number = $getLastNumber->number + 1;


            $partCreate = (new static)::create([
                'plateThick' => $plateThick,
                'typePlateThick' => $typePlateThick,
                'coatId' => $coatId,
                'number' => $number,
                'topCoatId' => $topCoatId,
                'primaryCoatId' => $primaryCoatId,
                'plate_types_id' => $plate_types_id,
                'description' => $description,
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
                'run_id' => $run_id,
            ]);
            $partCreate->save();
            $part = (new static)::with([
                'notes',
                'topCoat',
                'chromate',
                'coat',
                'plateType',
            ])->find($partCreate->id);
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Part was created successfully',
                'value' => $part

            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }

    public static function updatePart($id, $request)
    {
        DB::beginTransaction();
        try {
            $part = (new static)::find($id);
            if ($request->plate_types_id == '' || $request->plate_types_id == 0) $request->plate_types_id = null;
            if ($request->primaryCoatId == '' || $request->primaryCoatId == 0) $part->primaryCoatId = null;
            if ($request->coatId == '' || $request->coatId == 0) $request->coatId = null;
            if ($request->topCoatId == '' || $request->topCoatId == 0) $request->topCoatId = null;
            if ($request->plate_types_id == '' || $request->plate_types_id == 0) $request->plate_types_id = null;


            if ($request->primaryCoatId == null) {
                $request->primaryPer = null;
                $request->primaryPH = null;
                $request->primaryDiptime = null;
                $request->primaryTemp = null;
            }
            if ($request->coatId == null) {
                $request->coatPer = null;
                $request->coatPH = null;
                $request->coatDiptime = null;
                $request->coatTemp = null;
            }
            if ($request->topCoatId == null) {
                $request->topCoatPH = null;
                $request->topCoatTemp = null;
                $request->topCoatDiptime = null;
                $request->topCoatPer = null;
            }

            $part->description = $request->description;
            $part->topCoatPer = $request->topCoatPer;
            $part->topCoatTemp = $request->topCoatTemp;
            $part->topCoatPH = $request->topCoatPH;
            $part->topCoatDiptime = $request->topCoatDiptime;
            $part->primaryPer = $request->primaryPer;
            $part->primaryTemp = $request->primaryTemp;
            $part->primaryPH = $request->primaryPH;
            $part->plate_types_id = $request->plate_types_id;
            $part->primaryCoatId = $request->primaryCoatId;
            $part->coatId = $request->coatId;
            $part->topCoatId = $request->topCoatId;
            $part->primaryDiptime = $request->primaryDiptime;
            $part->coatPer = $request->coatPer;
            $part->coatTemp = $request->coatTemp;
            $part->coatPH = $request->coatPH;
            $part->coatDiptime = $request->coatDiptime;
            $part->plateThick = $request->plateThick;
            $part->typePlateThick = $request->typePlateThick;
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
            $run->plateThick = $request->plateThick;
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
    public function runs()
    {
        return $this->hasMany(Run::class);
    }
    public function plateType()
    {
        return $this->hasOne(Chemical::class, 'id', 'plate_types_id');
    }
    public function topCoat()
    {
        return $this->hasOne(Chemical::class, 'id', 'topCoatId');
    }
    public function chromate()
    {
        return $this->hasOne(Chemical::class, 'id', 'primaryCoatId');
    }
    public function coat()
    {
        return $this->hasOne(Chemical::class, 'id', 'coatId');
    }
}

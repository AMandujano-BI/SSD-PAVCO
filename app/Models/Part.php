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
        'created_at'
    ];

    public static function getPartsByRun($id)
    {
        $parts = (new static)::with([
            'notes',
            'topCoat',
            'chromate',
            'coat',
            'plateType',
        ])->where('run_id', $id)->get();
        return $parts;
    }
    public static function createPart($request)
    {
        DB::beginTransaction();
        try {
            $plateThick = $request->plateThick;
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

            $partCreate = (new static)::create([
                'plateThick' => $plateThick,
                'coatId' => $coatId,
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
            $part->plateThick= $request->plateThick;
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

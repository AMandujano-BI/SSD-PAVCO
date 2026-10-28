<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Part extends Model
{
    use HasFactory;

    public const MAX_PLATE_LAYERS = 6;

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
            'plateLayers.plateType',
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
            $plateLayers = static::resolvePlateLayers($request->all());
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


            if ($primaryCoatId == '' || $primaryCoatId == 0) $primaryCoatId = null;
            if ($coatId == '' || $coatId == 0) $coatId = null;
            if ($topCoatId == '' || $topCoatId == 0) $topCoatId = null;


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
                ->where('number', DB::raw("(select max(`number`) from parts where run_id = $run_id)"))
                ->first();
            if ($getLastNumber == null) {
                $number = 1;
            } else {

                $number = $getLastNumber->number + 1;
            }


            $partCreate = (new static)::create([
                'coatId' => $coatId,
                'number' => $number,
                'topCoatId' => $topCoatId,
                'primaryCoatId' => $primaryCoatId,
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
            static::syncPlateLayers($partCreate, $plateLayers);
            $part = (new static)::with([
                'notes',
                'topCoat',
                'chromate',
                'coat',
                'plateType',
                'plateLayers.plateType',
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
            $plateLayers = static::resolvePlateLayers($request->all());
            if ($request->primaryCoatId == '' || $request->primaryCoatId == 0) $part->primaryCoatId = null;
            if ($request->coatId == '' || $request->coatId == 0) $request->coatId = null;
            if ($request->topCoatId == '' || $request->topCoatId == 0) $request->topCoatId = null;


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
            $part->primaryCoatId = $request->primaryCoatId;
            $part->coatId = $request->coatId;
            $part->topCoatId = $request->topCoatId;
            $part->primaryDiptime = $request->primaryDiptime;
            $part->coatPer = $request->coatPer;
            $part->coatTemp = $request->coatTemp;
            $part->coatPH = $request->coatPH;
            $part->coatDiptime = $request->coatDiptime;
            $part->save();
            static::syncPlateLayers($part, $plateLayers);
            $run = Run::find($request->run_id);
            if ($run == null) {

                DB::rollBack();
                return [
                    'ok' => false,
                    'message' => 'Run not found',
                    'value' => 0,
                ];
            }
            $run->plateThick = $part->plateThick;
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

    /**
     * Normalizes the plate layers sent by a client. Accepts the new
     * `plate_layers` array, or falls back to the legacy single-layer fields
     * (plate_types_id / plateThick / typePlateThick). Layers without a plate
     * type are dropped.
     */
    public static function resolvePlateLayers(array $data): array
    {
        if (isset($data['plate_layers']) && is_array($data['plate_layers'])) {
            $raw = $data['plate_layers'];
        } else {
            $raw = [[
                'plate_type_id' => $data['plate_types_id'] ?? null,
                'thickness' => $data['plateThick'] ?? null,
                'thickness_unit' => $data['typePlateThick'] ?? null,
            ]];
        }

        $blankToNull = function ($value) {
            return ($value === null || $value === '') ? null : (string) $value;
        };

        $layers = [];
        foreach ($raw as $layer) {
            $plateTypeId = $layer['plate_type_id'] ?? null;
            if ($plateTypeId === null || $plateTypeId === '' || $plateTypeId == 0) {
                continue;
            }
            $layers[] = [
                'plate_type_id' => $plateTypeId,
                'thickness' => $blankToNull($layer['thickness'] ?? null),
                'thickness_unit' => $blankToNull($layer['thickness_unit'] ?? null),
                'per' => $blankToNull($layer['per'] ?? null),
                'temp' => $blankToNull($layer['temp'] ?? null),
                'ph' => $blankToNull($layer['ph'] ?? null),
                'diptime' => $blankToNull($layer['diptime'] ?? null),
            ];
        }

        if (count($layers) > static::MAX_PLATE_LAYERS) {
            throw new \InvalidArgumentException('A part can have at most ' . static::MAX_PLATE_LAYERS . ' plate layers');
        }

        return $layers;
    }

    /**
     * Replaces the part's plate layers and keeps the legacy single-plate
     * columns (plate_types_id, plateThick, typePlateThick) mirrored from the
     * first layer, since reports, PDFs and filters still read them.
     */
    public static function syncPlateLayers(Part $part, array $layers): void
    {
        $part->plateLayers()->delete();
        foreach (array_values($layers) as $index => $layer) {
            $part->plateLayers()->create($layer + ['position' => $index + 1]);
        }

        $first = $layers[0] ?? null;
        $part->forceFill([
            'plate_types_id' => $first['plate_type_id'] ?? null,
            'plateThick' => $first['thickness'] ?? null,
            'typePlateThick' => $first['thickness_unit'] ?? null,
        ])->save();
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
    public function plateLayers()
    {
        return $this->hasMany(PartPlateLayer::class)->orderBy('position');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Carbon\Carbon;
use DateTime;

class Run extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'number',
        'startDate',
        'description',
        'status',
        'dateCompleted',
        'plateThick',
        'primaryPer',
        'topCoatPer',
        'plate_methods_id',
        'company_id',
        'user_id',
        'created_at',
        'updated_at',
        'isEdit',
        'isReport',
        'last_edit',
        'hours',
        'closed_date',
    ];


    public function getlastEditAttribute($value)
    {
        if ($value != null) {
            return (new Carbon($value))->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z');
        } else {
            return $value;
        }
    }
    public function getclosedDateAttribute($value)
    {

        if ($value != null) {
            return (new Carbon($value))->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z');
        } else {
            return $value;
        }
    }



    public static function getAllRun($status)
    {
        $user = auth()->user();
        if ($status == 3) {
            $run = (new static)::with([
                'notes',
                'photos',
                'parts',
                'method',
                'parts.chromate',
                'parts.coat',
                'parts.plateType',
                'parts.topCoat',
                'company',
            ])
                ->where('status', '!=', 2)
                ->where('user_id', $user->id)
                ->get();
            return $run;
        } else {

            $run = (new static)::with([
                'notes',
                'photos',
                'parts',
                'method',
                'parts.chromate',
                'parts.coat',
                'parts.plateType',
                'parts.topCoat',
                'company',
            ])
                ->where('status', '!=', 2)
                ->where('status', $status)
                ->where('user_id', $user->id)
                ->get();
            return $run;
        }
    }

    public static function getRun($id)
    {
        $user = auth()->user();
        $run = (new static)::with([
            'notes',
            'photos',
            'parts',
            'method',
            'parts.chromate',
            'parts.coat',
            'parts.plateType',
            'parts.topCoat',
            'company',
        ])
            ->where('status', '!=', 2)
            ->where('user_id',$user->id)
            ->find($id);
        return $run;
    }

    public static function createRun($request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            $startDate = $request->startDate;
            $description = $request->description;
            $plate_types_id = $request->plate_types_id;
            $primaryCoatId = $request->primaryCoatId;
            $company_id= $request->company_id;
            $coatId = $request->coatId;
            $topCoatId = $request->topCoatId;
            $plate_methods_id = $request->plate_methods_id;
            $numberParts = $request->numberParts;

            $run = (new static)::create([
                'startDate' => $startDate,
                'number' => 1,
                'description' => $description,
                'company_id' => $company_id,
                'plateThick' => 0,
                'primaryPer' => 0,
                'coatPer' => 0,
                'topCoatPer' => 0,
                'plate_methods_id' => $plate_methods_id,
                'user_id' => $user->id,
            ]);
            $run->save();


            $plateThick = $request->plateThick;
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

            for ($i = 0; $i < $numberParts; $i++) {
                $parts = Part::create([
                    'plateThick' => $plateThick,
                    'description' => 'is a description',
                    'plate_types_id' => $plate_types_id,
                    'primaryCoatId' => $primaryCoatId,
                    'coatId' => $coatId,
                    'topCoatId' => $topCoatId,
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
                'message' => $e->getMessage(),
                'value'=>0
            ];
            // return $e->getMessage();
        }
    }

    public static function closeRun($id)
    {
        DB::beginTransaction();
        try {
            $run = (new static)::find($id);
            $run->status = 1;
            $run->closed_date = Carbon::now();
            $totalHours = 0;
            if ($run->isEdit) {
                $currentDate = Carbon::now('UTC');
                $lastDateEdit = new DateTime($run->last_edit);
                $lastDate = $lastDateEdit->format('Y-m-d H:i:s');
                $current = $currentDate->format('Y-m-d H:i:s');
                $hourdiff = (strtotime($current) - strtotime($lastDate)) / 3600;
                $hourRounded = bcdiv($hourdiff, '1', 0);
                $hours = intval($hourRounded, 10);

                $totalHours = $hours + $run->hours;
            } else {
                $currentDate = new DateTime();
                $current = $currentDate->format('Y-m-d H:i:s');
                $created_at = new DateTime($run->created_at);
                $createdDate = $created_at->format('Y-m-d H:i:s');
                $hourdiff = (strtotime($current) - strtotime($createdDate)) / 3600;
                $hourRounded = bcdiv($hourdiff, '1', 0);
                $totalHours = intval($hourRounded, 10);
            }
            $run->hours = $totalHours;

            $run->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Run was update successfully',
                'value' => $run,
            ];
        } catch (\Exception $e) {

            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function reopenRun($id)
    {
        DB::beginTransaction();
        try {
            $run = (new static)::find($id);
            $run->status = 0;
            $run->isEdit = true;
            $run->last_edit = Carbon::now();
            $run->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Run was update successfully',
                'value' => $run,
            ];
        } catch (\Exception $e) {

            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function deleteRun($id)
    {
        DB::beginTransaction();
        try {
            $run = (new static)::find($id);
            $run->status = 2;
            $run->company_id= null;
            $run->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Run was deleted successfully',
                'value' => $run,
            ];
        } catch (\Exception $e) {

            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public static function updateRunPart($id, $request)
    {
        DB::beginTransaction();
        try {
            $run = (new static)::find($id);
            // $run->status = 1;
            $run->startDate = $request->startDate;
            $run->description = $request->description;
            $run->plate_methods_id = $request->plate_methods_id;
            $run->company_id = $request->company_id;
            if ($request->hasDiferentHours) {
                $run->hours = $request->hours;
                $run->last_edit = Carbon::parse($request->last_edit, 'UTC');
                $run->isEdit = true;
            }
            $run->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Run was update successfully',
                'value' => $run,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public static function generateFile()
    {
    }


    // ===================================RELATIONS

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function method()
    {
        return $this->hasOne(PlateMethod::class, 'id', 'plate_methods_id');
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    public function parts()
    {
        return $this->hasMany(Part::class);
    }
    public  function company(){
        return $this->belongsTo(Company::class);
    }
}

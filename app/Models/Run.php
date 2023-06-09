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
        'start_date',
        'description',
        'status',
        'dateCompleted',
        'plateThick',
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
    public function getstartDateAttribute($value)
    {
        if ($value != null) {
            return (new Carbon($value))->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z');
        } else {
            return $value;
        }
    }



    private static function checkIfAdministrator()
    {
        $user = auth()->user();
        $userWithRol  = User::where('id', $user->id)->with(['rols'])->first();
        $rols = $userWithRol->rols;
        foreach ($rols as $key) {
            if ($key->name == "Master Administrator" || $key->name == "Administrator" || $key->name == "Employee") {
                return true;
            }
            return false;
        }
    }
    private static function checkIfDistributor()
    {
        $user = auth()->user();
        $userWithRol  = User::where('id', $user->id)->with(['rols'])->first();
        $rols = $userWithRol->rols;
        foreach ($rols as $key) {
            if ($key->name == "Distributor") {
                return true;
            }
            return false;
        }
    }
    public static function getAllRun($status)
    {
        $user = auth()->user();
        $isAdministrator = (new static)::checkIfAdministrator();
        $isDistributor = (new static)::checkIfDistributor();
        if ($isAdministrator) {
            //Si es de tipo administrador no se  hara el filtrado de runs, sino se mostratará todos los runs
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
                    ->get();
                return $run;
            }
        } else if ($isDistributor) {
            //Si es de tipo distribuidor,se obtiene todas las compañias asociadas a ese distribuidor
            $user = auth()->user();
            $customers = Company::where('company_id', $user->company_id)->get(['id']);
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
                    ->whereIn('company_id', $customers)
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
                    ->whereIn('company_id', $customers)
                    ->get();
                return $run;
            }
        }



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
                // ->where('user_id', $user->id)
                ->where('company_id', $user->company_id)
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
                ->where('company_id', $user->company_id)
                ->get();
            return $run;
        }
    }

    public static function getAllRunByDate($startDate, $endDate, $status)
    {
        $startDate = str_replace("'", '', $startDate);
        $endDate = str_replace("'", '', $endDate);
        if ($status == 3) {
            $run = (new static)::with([
                'method',

                'company',
            ])
                ->where('status', '!=', 2)
                ->whereBetween('runs.start_date', [$startDate, $endDate])
                ->get();
            return $run;
        } else {

            $run = (new static)::with([
                'method',

                'company',
            ])
                ->where('status', '!=', 2)
                ->where('status', $status)
                ->whereBetween('runs.start_date', [$startDate, $endDate])
                ->get();
            return $run;
        }
    }






    public static function getRun($id)
    {
        $user = auth()->user();
        $isAdministrator = (new static)::checkIfAdministrator();
        $isDistributor = (new static)::checkIfDistributor();

        if ($isAdministrator) {
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
                ->find($id);
            return $run;
        } else if ($isDistributor) {
            $customers = Company::where('company_id', $user->company_id)->get(['id']);
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
                // ->where('company_id', $user->company_id)
                ->whereIn('company_id', $customers)
                ->find($id);
            return $run;
        }

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
            ->where('company_id', $user->company_id)
            ->find($id);
        return $run;
    }

    public static function updateParts($request)
    {
        DB::beginTransaction();
        try {
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
                ->find($request->runId);

            $runHours = $request->hours;


            foreach ($run->parts as $runPart) {                             // se recorren parts del run
                for ($j = 0; $j < count($request->parts); $j++) {            // Se recorre el Request
                    if ($runPart->id == $request->parts[$j]['id']) {        // se comparan los ids
                        $part = Part::find($runPart->id);

                        if ($request->parts[$j]['rs'] == true && $runPart->isRs == null) {   // primera vez checkeado
                            $part->isRs = true;
                            $part->hoursRs = $runHours;
                        } else {
                            // dd($request->parts[$j]['hoursRs'], $runPart->isRs);
                            if (is_null($request->parts[$j]['hoursRs']) && $runPart->isRs == true) {
                                // dd('void', $request->parts[$j]['id']);
                                $part->isRs = false;
                                $part->hoursRs = null;
                            } else {
                                $part->hoursRs = $request->parts[$j]['hoursRs'];
                            }
                        }
                        // dd($request->parts[$j]['ws'], $runPart->isWs, $request->parts[$j]['hoursWs']);
                        if ($request->parts[$j]['ws'] == true && $runPart->isWs == null) { // primera vez checkeado
                            $part->isWs = true;
                            $part->hoursWs = $runHours;
                        } else {
                            // dd( is_null($request->parts[$j]['hoursWs']) ,  is_null($runPart->isWs));
                            if (is_null($request->parts[$j]['hoursWs']) && $runPart->isWs == true) {
                                $part->isWs = false;
                                $part->hoursWs = null;
                            } else {
                                $part->hoursWs = $request->parts[$j]['hoursWs'];
                            }
                        }

                        $part->save();
                    }
                }
            }

            // $run->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Run was updated successfully',
                'value' => $run,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }



        // return $run;
    }

    // Agregar 
    public static function updateHours($request)
    {
        DB::beginTransaction();
        try {

            $ifExist = Hour::where('dateChange', $request->date)->first();
            if ($ifExist) {
                DB::rollBack();
                return [
                    'ok' => false,
                    'message' => 'There is already a date added'
                ];
            }
            $hour = Hour::create([
                'hourNumber' => $request->hours,
                'dateChange' => $request->date,
                'user_id' => auth()->user()->id,
            ]);


            $run = (new static)->where('status', 0)
                ->where('start_date','<', $request->date)
                ->get();

            foreach ($run as $runItem) {
                $updatedRun = Run::find($runItem->id);
                $updatedRun->hours = $updatedRun->hours + $request->hours;
                $updatedRun->save();
            }

            // $run = DB::table('run')
            //                 ->whereDate('start_date', $formated_date);

            $hour->save();

            DB::commit();

            return [
                'ok' => true,
                'message' => 'Hours was add successfully',
                'value' => $hour,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    // Editar
    public static function updateHoursUpdate($id, $request)
    {
        DB::beginTransaction();
        try {
            // $run = (new static)->where('')

            $hour = Hour::where('id', $id)->first();
            $hourAge = $hour->hourNumber;
            if (!$hour) {
                DB::rollBack();
                return [
                    'ok' => false,
                    'message' => 'Hour not found'
                ];
            }
            $hour->hourNumber = $request->hours;
            $hour->user_id = auth()->user()->id;

            $run = (new static)->where('status', 0)
                ->where('start_date','<', $hour->dateChange)
                ->get();

         

            foreach ($run as $runItem) {
                $updatedRun = Run::find($runItem->id);
                $hoursResult = $updatedRun->hours - $hourAge;

                if($hoursResult <0){
                    if($updatedRun->hours ==0){
                        $updatedRun->hours =  $request->hours;
                    }else{
                        $updatedRun->hours =  0;
                    }
                }else{
                    $updatedRun->hours =  $hoursResult + $request->hours;
                }
                $updatedRun->save();
            }


            $hour->save();
            DB::commit();

            return [
                'ok' => true,
                'message' => 'Hours was update successfully',
                'value' => $hour,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public static function deleteHour($id)
    {
        DB::beginTransaction();
        try {
            $hour = Hour::where('id', $id)->first();
            
            $run = (new static)->where('status', 0)
                // ->whereDate('start_date', $hour->dateChange)
                ->where('start_date','<', $hour->dateChange)
                ->get();

            foreach ($run as $runItem) {
                $updatedRun = Run::find($runItem->id);
                $hoursResult = $updatedRun->hours - $hour->hourNumber;
                if($hoursResult <0){
                    $updatedRun->hours =0;
                }else{
                    $updatedRun->hours =$hoursResult;
                }
                $updatedRun->save();
            }
            $hour->delete();

            DB::commit();

            return [
                'ok' => true,
                'message' => 'Hours was delete successfully',
                'value' => $hour,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public static function createRun($request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $countParts = 1;
            $start_date = $request->start_date;
            $description = $request->description;
            $plate_methods_id = $request->plate_methods_id;
            $company_id = $request->company_id;


            $run = (new static)::create([
                'start_date' => $start_date,
                'number' => 1,
                'description' => $description,
                'company_id' => $company_id,
                'plateThick' => 0,
                'plate_methods_id' => $plate_methods_id,
                'user_id' => $user->id,
            ]);
            $run->save();

            $parts =  $request->parts;

            for ($j = 0; $j < count($request->parts); $j++) {
                $typePlateThick = $request->parts[$j]['typePlateThick'] ?? null;
                $plateThick = $request->parts[$j]['plateThick'] ?? null;
                $topCoatPer = $request->parts[$j]['topCoatPer'] ?? null;
                $topCoatTemp = $request->parts[$j]['topCoatTemp'] ?? null;
                $topCoatPH = $request->parts[$j]['topCoatPH'] ?? null;
                $topCoatDiptime = $request->parts[$j]['topCoatDiptime'] ?? null;
                $primaryTemp = $request->parts[$j]['primaryTemp'] ?? null;
                $primaryPer = $request->parts[$j]['primaryPer'] ?? null;
                $primaryPH = $request->parts[$j]['primaryPH'] ?? null;
                $primaryDiptime = $request->parts[$j]['primaryDiptime'] ?? null;
                $coatPer = $request->parts[$j]['coatPer'] ?? null;
                $coatTemp = $request->parts[$j]['coatTemp'] ?? null;
                $coatPH = $request->parts[$j]['coatPH'] ?? null;
                $coatDiptime = $request->parts[$j]['coatDiptime'] ?? null;
                $numberParts = $request->parts[$j]['numberParts'];


                $primaryCoatId = $request->parts[$j]['primaryCoatId'] ?? null;
                $coatId = $request->parts[$j]['coatId'] ?? null;
                $topCoatId = $request->parts[$j]['topCoatId'] ?? null;

                $plate_types_id = $request->parts[$j]['plate_types_id'] ?? null;

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



                for ($i = 0; $i < $numberParts; $i++) {
                    $parts = Part::create([
                        'plateThick' => $plateThick,
                        'number' => $countParts,
                        'typePlateThick' => $typePlateThick,
                        'description' => 'Part number ' . $countParts,
                        'plate_types_id' => $plate_types_id,
                        'primaryCoatId' => $primaryCoatId,
                        'coatId' => $coatId,
                        'topCoatId' => $topCoatId,
                        'topCoatTemp' => $topCoatTemp,
                        'topCoatPer' => $topCoatPer,
                        'topCoatPH' => $topCoatPH,
                        'topCoatDiptime' => $topCoatDiptime,
                        'primaryPer' => $primaryPer,
                        'primaryTemp' => $primaryTemp,
                        'primaryPH' => $primaryPH,
                        'primaryDiptime' => $primaryDiptime,
                        'coatTemp' => $coatTemp,
                        'coatPer' => $coatPer,
                        'coatPH' => $coatPH,
                        'coatDiptime' => $coatDiptime,
                        'run_id' => $run->id,
                    ]);
                    $countParts++;
                    $parts->save();
                }
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
                'value' => 0
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
            $run->hoursClosed = $run->hours;
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
            $run->company_id = null;
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
            // dd($request->start_date_edit);
            $run->start_date = $request->start_date;
            $run->description = $request->description;
            $run->plate_methods_id = $request->plate_methods_id;
            $run->company_id = $request->company_id;
            $run->hours = $request->hours;

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
    public  function company()
    {
        return $this->belongsTo(Company::class);
    }
}

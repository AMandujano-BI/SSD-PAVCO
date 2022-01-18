<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'hours',
        'image',
        'description',
        'report',
        'run_id',
        'created_at',
        'last_edit',
        'closed_date',
        'isEdit',
    ];
    // protected $casts = [
    //     'created_at' => 'datetime:Y-m-d',
    // ];
    public function getlastEditAttribute($value)
    {
        if ($value != null) {
            return (new Carbon($value))->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z');
        } else {
            return $value;
        }
    }
    public function getImageAttribute($value)
    {
        $image =  Storage::temporaryUrl($value, now()->addMinutes(30));
        return $image;
    }
    public static function getPhotosByRun($run_id){
        $photos = (new static)::where('run_id',$run_id)->get();
        return $photos;

    }
    public static function createPhoto($request)
    {

        DB::beginTransaction();
        try {

            //Prepare Data
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $run_id = $request->input('run');
            $name = $request->input('name');
            $hours = $request->input('hours');
            $description = $request->input('description');
            $run_id = $request->input('run');
            $report = $request->input('report');

            // return $file;
            //Save image in AWS
            $initialHours = 0;
            //Save Photo in database
            $photo = (new static)::create([
                'name' => $name,
                'hours' => $initialHours,
                'image' => 'images/run' . $run_id . '/' . $filename,
                'description' => $description,
                'report' => $report,
                'run_id' => $run_id,
            ]);
            $photo->save();
            $file_name = 'images/run' . $run_id.'/'.$filename;
            // dd($file);

            // dd($file_name);
            // $image = $file->storeAs('images/run' . $run_id, $filename, 's3');
            Storage::put($file_name,file_get_contents($file), 's3');

            DB::commit();
            return [
                'ok' => true,
                'message' => 'Photo was created successfully',
                'value' => $photo,
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

    public static function updatePhoto($request)
    {

        DB::beginTransaction();
        try {

            $photo = (new static)::find($request->id);
            $photo->name = $request->name;
            $photo->description = $request->description;
            $photo->report = $request->report;
            if ($request->hasDiferentHours) {
                $photo->isEdit = true;
                $photo->last_edit = $request->last_edit;
                $photo->hours = $request->hours;
            }
            $photo->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Photo was updated successfully',
                'value' => $photo,
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
    public static function  deletePhoto($id)
    {
        DB::beginTransaction();
        try {

            $photoDeleted = (new static)::find($id);
            $image = $photoDeleted->getRawOriginal('image');

            if (Storage::disk('s3')->exists($image)) {
                $photoDeleted->delete();
                Storage::disk('s3')->delete($image);
                DB::commit();
                return [
                    'ok' => true,
                    'message' => 'Photo was deleted successfully',
                    'value' => $photoDeleted->id,
                ];
            } else {
                DB::rollback();
                return [
                    'ok' => false,
                    'message' => 'Image not found',
                    'value' => 0
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }

    // ===================================RELATIONS

    public function run()
    {
        return $this->belongsTo(Run::class);
    }
}

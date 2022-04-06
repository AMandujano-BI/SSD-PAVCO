<?php

namespace App\Models;

use App\Http\Controllers\PhotoController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
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
        $image =  Storage::temporaryUrl($value, now()->addDays(4));
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
            $description = $request->input('description');
            $run_id = $request->input('run');
            $photoHours = $request->input('photoHours');
            $report = $request->input('report');
           
            $file_name = 'images/run' . $run_id.'/'.$filename;
            $photoEqualFileName = (new static)::where('image',$file_name)->first();
            if($photoEqualFileName){
                return [
                    'ok' => false,
                    'message' => 'Photo already exists',
                    'value' => 0
                ];  
            }
            $photo = (new static)::create([
                'hours' => $photoHours,
                'image' =>$file_name, 
                'description' => $description,
                'report' => $report,
                'run_id' => $run_id,
            ]);
            $photo->save();
   
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
            $photo->description = $request->description;
            $photo->report = $request->report;
            $photo->hours = $request->hours;
            
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

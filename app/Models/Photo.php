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
    ];
    public function getImageAttribute($value)
    {
        // dd(now());
        $image =  Storage::temporaryUrl($value, now()->addMinutes(5));
        // dd($image);
        $this->attributes['image2'] = $image;
        // $this->attributes['image'] = 'fdsafdas';
        return $image;
        //  dd($this->attributes['image']);
    }
    public static function createPhoto($request)
    {

        DB::beginTransaction();
        try {

            //Prepare Data
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $run_id = $request->input('run');
            $hours = $request->input('hours');
            $description = $request->input('description');
            $run_id = $request->input('run');
            $report = $request->input('report');
            $image = $file->storeAs('images/run' . $run_id, $filename, 's3', 'public');

            //Save Photo in database
            $photo = (new static)::create([
                'name' => $filename,
                'hours' => Carbon::now(),
                'image' => $image,
                'description' => $description,
                'report' => $report,
                'run_id' => $run_id,
            ]);
            $photo->save();


            //Save image in AWS
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
    // ===================================RELATIONS

    public function run()
    {
        return $this->belongsTo(Run::class);
    }
}

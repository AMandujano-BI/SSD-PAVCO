<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Run extends Model
{
    use HasFactory;
    protected $fillable =[
        'description',
        'plate_types_id'
    ];


    public static function getAllRun(){
        $run = (new static)::all();
        return $run;
    }

    public static function createRun($request)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            $run = (new static)::create([]);
            $run->save();
            return 'okar';
        } catch (\Exception $e) {
            DB::rollback();
            return [];
        }
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlateMethod extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'id'
    ];
    public static function getPlateMethods()
    {
        $plateMethod = (new static)::all(['id AS value','name AS label']);
        return $plateMethod;
    }


    //---------------RELATIONS

    // public function run()
    // {
    //     return $this->hasOne(Run::class);
    // }
}

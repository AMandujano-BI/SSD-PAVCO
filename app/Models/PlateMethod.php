<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlateMethod extends Model
{
    use HasFactory;
    protected $fillable =[
        'name'
    ];
    public static function getPlateMethods()
    {
        $plateMethod = (new static)::all();
        return $plateMethod;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlateType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public static function getPlateTypes()
    {
        $plateType= (new static)::all();
        return $plateType;
    }
}

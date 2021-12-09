<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'region',
        'area',
        'population',
        'gdp',
    ];

    public static function getCountries(){

        $countries = (new static)::all(['id AS value','name AS label']);
        return $countries;
    }
}

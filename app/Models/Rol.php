<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'name',
        'status'
    ];


    public static function getRols(){
        $rols = (new static)::where('status','!=',0)->get(['id AS value', 'name AS label']);
        return $rols;
    }
    public function users(){
        return $this->belongsToMany(User::class);
    }
}

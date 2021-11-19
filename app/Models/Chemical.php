<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chemical extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type'
    ];

    public static function getByType($type)
    {
        $chemicals = (new static)::where('type',$type)->orderBy('name', 'asc')->get();
        return $chemicals;
    }
}

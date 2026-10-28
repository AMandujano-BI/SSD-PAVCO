<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartPlateLayer extends Model
{
    protected $fillable = [
        'part_id',
        'position',
        'plate_type_id',
        'thickness',
        'thickness_unit',
        'per',
        'temp',
        'ph',
        'diptime',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function plateType()
    {
        return $this->belongsTo(Chemical::class, 'plate_type_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    // ===================================RELATIONS

    public function run()
    {
        return $this->belongsTo(Run::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;

    protected $fillable =[
        'hourNumber',
        'dateChange',
        'user_id'
    ];


    public static function getHours(){
        $hours= (new static)::with([
            'user'
        ])->orderBy('dateChange', 'desc')->get();
        return $hours;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

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

    public static function createChemical($request)
    {
        $name = $request->name;
        $type = $request->type;
        $chemical = (new static)::create([
            'name' => $name,
            'type' => $type
        ]);
        return [
            'ok' => true,
            'message' => 'Chemical was added successfully',
            'value' => $chemical,
        ];
    }

    public static function updateChemical($request)
    {
        $chemical = (new static)::find($request->id);
        $chemical->name = $request->name;
        $chemical->type = $request->type;
        $chemical->save();
        return [
            'ok' => true,
            'message' => 'Chemical was updated successfully',
            'value' => $chemical,
        ];
    }

    public static function deleteChemical($id) {
        try{

        $chemical = (new static)::find($id);
        $chemical->delete();
        return [
            'ok' => true,
            'message' => 'Chemical was deleted successfully',
            'value' => $chemical,
        ];

        }catch(\Exception $e){
            return [
                'ok' => false,
                'message' => 'The chemical is being used',
                'value' =>0
            ];
        }
    }

    public static function getByType($type)
    {
        $chemicals = (new static)::where('type',$type)->orderBy('name', 'asc')->get(['id AS value','name AS label']);
        return $chemicals;
    }
    //---------------Relations
    public function parts()
    {
        return $this->belongsTo(Part::class);
    }

    public static function getAllForCategory($categoryId) {
        if ($categoryId == 0){
            $chemicals = (new static)::orderBy('type','asc')->get();
            return $chemicals;
        } else{
            $chemicals = (new static)::where('type',$categoryId)->get();
            return $chemicals;
        }
    }
}

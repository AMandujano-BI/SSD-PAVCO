<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Note extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'note',
        'isPublic',
        'part_id',
        'run_id',
    ];

    public static function getNotes($isPublic)
    {
        $notes = (new static)::where('isPublic', $isPublic)->get();
        return $notes;
    }
    public static function createNote($request)
    {
        DB::beginTransaction();
        try {
            $firstname = $request->firstname;
            $lastname = $request->lastname;
            $note = $request->note;
            $part_id = $request->part_id;
            $run_id = $request->run_id;
            $isPublic = $request->isPublic;

            $note = (new static)::create([
                // 'firstname' => $firstname,
                'firstname' => 'default',
                // 'lastname' => $lastname,
                'lastname' => 'default',
                'note' => $note,
                'part_id' => $part_id,
                'run_id' => $run_id,
                'isPublic' => $isPublic,
            ]);
            $note->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Note was created successfully',
                'value' => $note,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' =>0
            ];
        }
    }
    // ===================================RELATIONS

    public function run()
    {
        return $this->belongsTo(Run::class);
    }
    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}

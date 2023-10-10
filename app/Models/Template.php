<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'section',
        'file_name',
        'file_data',
        'mime_type',
        'description',
        'status',
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    static public function getAdminFormTemplate(){
        return self::where('section', 'form')
        ->whereHas('lecturer', function ($query) {
            $query->where('role_as', 0) // Admin role
                  ->orWhere('role_as', 1); // Lecturer role
        })
        ->select('templates.*')
        // ->get();
        ->paginate(2);
    }

    static public function getSingle($id){
        return self::find($id);
    }
}

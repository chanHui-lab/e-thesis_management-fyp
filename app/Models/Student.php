<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'stu_id'; // Specify the custom primary key column name

    protected $fillable = ['stu_id', 'matric_number','supervisor_id'];

    // Yes, if the foreign key column in your "students" table is named stu_id,
    // you should use stu_id in your Eloquent relationships and migrations to
    // specify that it is the foreign key connecting students to the "users" table.
    public function user()
    {
        return $this->belongsTo(User::class, 'stu_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }

    public function formSubmissions()
    {
        return $this->hasMany(Form_submission::class, 'student_id');
    }
}

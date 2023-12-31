<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Notifications\DatabaseNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_as'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function student()
    {
        // return $this->hasOne(Student::class);
        return $this->hasOne(Student::class, 'stu_id', 'id');

    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class, 'lecturer_id', 'id');
    }

    public function formSubmissions()
    {
        return $this->hasManyThrough(Form_submission::class, Student::class, 'supervisor_id', 'student_id');    }

    public function thesisSubmissions()
    {
        return $this->hasManyThrough(Thesis_submission::class, Student::class, 'supervisor_id', 'student_id');    }

    // public function advisor()
    // {
    //     return $this->hasOne(Advisor::class);
    // }
    public function proposalSubmissions()
    {
        return $this->hasMany(Proposal_submission::class, 'submission_post_id');
    }

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }
}

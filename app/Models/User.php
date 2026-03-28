<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_picture',
        'birthdate',
        'student_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birthdate'         => 'date',
        'email_verified_at' => 'datetime',
    ];

    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function isAdmin(): bool
    {
        return strtolower($this->role) === 'admin';
    }

    // ── Relationships ──────────────────────────────
    public function scholarships()
    {
        return $this->hasMany(Scholarship::class, 'created_by', 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id', 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id', 'user_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'user_id', 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(SmsNotification::class, 'user_id', 'user_id');
    }
}


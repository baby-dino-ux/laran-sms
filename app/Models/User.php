<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// ✅ Import related models
use App\Models\Scholarship;
use App\Models\Application;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ── Relationships ──────────────────────────────
    public function scholarships()
    {
        return $this->hasMany(Scholarship::class, 'created_by', 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id', 'user_id');
    }

    // ── Helpers ────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    // ── Token table key fix ────────────────────────
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }
}

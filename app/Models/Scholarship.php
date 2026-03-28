<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// ✅ Import related models
use App\Models\User;
use App\Models\Application;

class Scholarship extends Model
{
    use HasFactory;

    // Primary key
    protected $primaryKey = 'scholarship_id';

    // Mass assignable fields
    protected $fillable = [
        'scholarship_name',
        'description',
        'amount',
        'deadline',
        'created_by',
        'status',
    ];

    // Casts for attributes
    protected $casts = [
        'deadline' => 'date',
        'amount'   => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────

    // Scholarship belongs to a creator (Admin/User)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    // Scholarship has many applications
    public function applications()
    {
        return $this->hasMany(Application::class, 'scholarship_id', 'scholarship_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Application;

class Scholarship extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'scholarship_id';

    protected $fillable = [
        'scholarship_name',
        'description',
        'amount',
        'slots',
        'deadline',
        'created_by',
        'status',
        'eligibility_criteria',
    ];

    protected $casts = [
        'deadline'             => 'date',
        'amount'               => 'decimal:2',
        'eligibility_criteria' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'scholarship_id', 'scholarship_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'scholarship_id', 'scholarship_id');
    }
}


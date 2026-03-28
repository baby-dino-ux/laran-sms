<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scholarship_id',
        'application_id',
        'amount_granted',
        'award_date',
        'notes',
        'notification_sent',
    ];

    protected $casts = [
        'award_date'        => 'date',
        'amount_granted'    => 'decimal:2',
        'notification_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'scholarship_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }
}

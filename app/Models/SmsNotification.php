<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sms_notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'email_sent',
        'read_at',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'email_sent' => 'boolean',
        'read_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

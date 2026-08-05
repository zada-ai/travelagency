<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'visa_application_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationship: A notification belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A notification belongs to a VisaApplication
    public function visaApplication()
    {
        return $this->belongsTo(VisaApplication::class);
    }

    // Mark notification as read
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    // Get unread count for a user
    public static function unreadCount($userId)
    {
        return self::where('user_id', $userId)->where('is_read', false)->count();
    }
}

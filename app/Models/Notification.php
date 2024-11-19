<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'message',
        'read_status'
    ];

    public static function markAsRead($notificationId)
    {
        return self::where('id', $notificationId)->update(['read_status' => 1]);
    }
}

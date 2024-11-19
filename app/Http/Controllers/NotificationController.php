<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnReadNotifications(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        $notifications = Notification::where('warehouse_id', $warehouseId)->where('read_status', 0)->orderBy('created_at', 'desc')->get();
        info($notifications);
        return response()->json($notifications);
    }

    public function markAllAsRead()
    {
        Notification::where('read_status', 0)->update(['read_status' => 1]);
        return response()->json(['message' => 'Tất cả thông báo đã được đánh dấu là đã đọc!']);
    }
}

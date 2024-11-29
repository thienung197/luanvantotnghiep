<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnReadNotifications(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        $notifications = Notification::where('warehouse_id', $warehouseId)
            // ->where('read_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($notifications);
    }

    public function getUnReadNotificationCount(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        $count = Notification::where('warehouse_id', $warehouseId)
            ->where('read_status', 0)
            ->count();
        return response()->json($count);
    }

    public function markAllAsRead(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        Notification::where('warehouse_id', $warehouseId)
            ->where('read_status', 0)
            ->update(['read_status' => 1]);
        return response()->json(['message' => 'Tất cả thông báo đã được đánh dấu là đã đọc!']);
    }
}

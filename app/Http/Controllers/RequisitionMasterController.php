<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class RequisitionMasterController extends Controller
{
    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        try {
            Notification::truncate(); // Hapus semua notifikasi
            return response()->json(['message' => 'All notifications deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete notifications.'], 500);
        }
    }
}

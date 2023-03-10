<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Notification;

class NotificationApi extends Controller
{
    public function getAllNotification(){
        $user_id = 1;
        
        $notifn = Notification::select('notif_type', 'notif_body', 'notif_send_to', 'is_pending')
            ->where('is_pending', 0)
            ->where(function ($query) {
                $query->where('notif_send_to','LIKE','%send_to":"1"%') //Must use jsoncontains
                    ->orWhere('notif_send_to','LIKE','%send_to":"all"%');
            })
            ->get();

        if ($notif->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Notification Not Found',
                'data' => $notif
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Notification Found',
                'data' => $notif
            ], Response::HTTP_OK);
        }
    }
}

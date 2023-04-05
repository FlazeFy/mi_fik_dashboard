<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Query;

use App\Models\Notification;

class QueryNotification extends Controller
{
    public function getAllNotification(){    
        $notif = Notification::select('id', 'notif_type', 'notif_body', 'notif_send_to', 'is_pending','created_at','updated_at')
            ->where('is_pending', 0)
            ->where(function ($query) {
                $query->where('notif_send_to','LIKE','%send_to":"all"%');
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

    //FIx this !!!
    public function getMyNotification($user_id){    
        $select = Query::getSelectTemplate("notif_my");

        $notif = Notification::selectRaw($select)
            ->leftJoin('users', 'users.id', '=', 'notifications.created_by')
            ->leftJoin('admins', 'admins.id', '=', 'notifications.created_by')
            ->where('is_pending', 0)
            // ->where(function ($query) {
            //     $query->where('notif_send_to','LIKE','%send_to":"'.$user_id.'"%') //Must use jsoncontains
            //         ->orWhere('notif_send_to','LIKE','%send_to":"all"%');
            // })"send_to":"all"
            ->whereRaw("notif_send_to LIKE '%".'"'."user_id".'"'.":".'"'.$user_id.'"'."%' 
                OR notif_send_to LIKE '%".'"'."send_to".'"'.":".'"'.'all"'."%'")
            ->paginate(12);

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

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
        $notif = Notification::select('id', 'notif_type', 'notif_title', 'notif_body', 'notif_send_to', 'is_pending','created_at','updated_at')
            ->where('is_pending', 0)
            ->where(function ($query) {
                $query->where('notif_send_to','LIKE','%send_to":"all"%');
            })
            ->get();

        if ($notif->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Notification Not Found',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Notification Found',
                'data' => $notif
            ], Response::HTTP_OK);
        }
    }

    public function getMyNotification(Request $request){
        try {
            $user_id = $request->user()->id;

            $select = Query::getSelectTemplate("notif_my");

            $notif = Notification::selectRaw($select)
                //->leftJoin('users', 'users.id', '=', 'notifications.created_by')
                ->leftJoin('admins', 'admins.id', '=', 'notifications.created_by')
                ->where('is_pending', 0)
                // ->where(function ($query) {
                //     $query->where('notif_send_to','LIKE','%send_to":"'.$user_id.'"%') //Must use jsoncontains
                //         ->orWhere('notif_send_to','LIKE','%send_to":"all"%');
                // })"send_to":"all"
                ->whereRaw("notif_send_to LIKE '%".'"'."id".'"'.":".'"'.$user_id.'"'."%'
                    OR notif_send_to LIKE '%".'"'."send_to".'"'.":".'"'.'all"'."%'")
                ->orderBy('notifications.created_at', 'DESC')
                ->paginate(12);

            if ($notif->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Notification Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification Found',
                    'data' => $notif
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

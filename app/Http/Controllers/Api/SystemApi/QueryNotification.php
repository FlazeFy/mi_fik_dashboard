<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Generator;
use App\Helpers\Query;

use App\Models\Notification;
use App\Models\User;
use App\Models\PersonalAccessTokens;

class QueryNotification extends Controller
{
    public function getMyNotification(Request $request){
        try {
            $user_id = $request->user()->id;

            $select = Query::getSelectTemplate("notif_my");

            $check = PersonalAccessTokens::where('tokenable_id', $user_id)->first();
            $notif = Notification::selectRaw($select)
                ->leftJoin('admins', 'admins.id', '=', 'notifications.created_by')
                ->where('is_pending', 0);
                
            if($check->tokenable_type === "App\\Models\\User"){
                $user = User::select("accepted_at")
                    ->where("id",$user_id)
                    ->first();

                $notif = $notif->whereRaw("notifications.created_at > '".date("Y-m-d H:i:s", strtotime($user->accepted_at))."'")
                    ->whereRaw("(notif_send_to LIKE '%".'"'."id".'"'.":".'"'.$user_id.'"'."%'
                        OR notif_send_to LIKE '%".'"'."send_to".'"'.":".'"'.'all"'."%')");
            }
           
            $notif = $notif->orderBy('notifications.created_at', 'DESC')
                ->paginate(12);

            if ($notif->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'notification', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'notification', null),
                    'data' => $notif
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

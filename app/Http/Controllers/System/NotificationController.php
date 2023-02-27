<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Models\Notification;
use App\Models\Dictionary;

class NotificationController extends Controller
{
    public function index()
    {
        //Required config
        $select_1 = "Notification";

        $notification = Notification::select('*')
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        $dictionary = Dictionary::select('slug_name','dct_name','dct_desc','type_name')
            ->join('dictionaries_types', 'dictionaries_types.app_code', '=', 'dictionaries.dct_type')
            ->where('type_name', $select_1)
            ->orderBy('dictionaries.created_at', 'ASC')
            ->get();

        //Set active nav
        session()->put('active_nav', 'system');

        return view ('system.notification.index')
            ->with('notification', $notification)
            ->with('dictionary', $dictionary);
    }

    public function update_notif(Request $request, $id)
    {
        $result = Notification::where('id', $id)->update([
            'notif_type' => $request->notif_type,
            'notif_body' => $request->notif_body,
            //'notif_send_to' => $request->notif_send_to,
            'is_pending' => $request->is_pending,
            'pending_until' => $pending_date,
            'updated_at' => date("Y-m-d h:i"),
            'updated_by' => 1 //for now
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification successfully updated',
            'result' => $result,
        ]);
    }
}

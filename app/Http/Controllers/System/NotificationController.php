<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;

use App\Models\Notification;
use App\Models\Dictionary;

class NotificationController extends Controller
{
    public function index()
    {
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
            //Required config
            $select_1 = "Notification";

            $notification = Notification::getAllNotification("DESC", "DESC");
            $dictionary = Dictionary::getDictionaryByType($select_1);
            $greet = Generator::getGreeting(date('h'));

            //Set active nav
            session()->put('active_nav', 'system');

            return view ('system.notification.index')
                ->with('notification', $notification)
                ->with('dictionary', $dictionary)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function update_notif(Request $request, $id)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        $result = Notification::where('id', $id)->update([
            'notif_type' => $request->notif_type,
            'notif_body' => $request->notif_body,
            //'notif_send_to' => $request->notif_send_to,
            'is_pending' => $request->is_pending,
            'pending_until' => $pending_date,
            'updated_at' => date("Y-m-d h:i"),
            'updated_by' => $user_id
        ]);

        return redirect()->back()->with('success_message', 'Notification has been updated');
    }

    public function delete_notif($id)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        
        $result = Notification::where('id', $id)->update([
            'deleted_at' => date("Y-m-d h:i"),
            'deleted_by' => $user_id
        ]);

        return redirect()->back()->with('success_message', "Notification has been deleted");
    }
}

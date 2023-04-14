<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;

use App\Models\Notification;
use App\Models\Dictionary;
use App\Models\Info;
use App\Models\Menu;

class NotificationController extends Controller
{
    public function index()
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        //Required config
        $select_1 = "Notification";

        $notification = Notification::getAllNotification("DESC", "DESC");
        $dictionary = Dictionary::getDictionaryByType($select_1);
        $greet = Generator::getGreeting(date('h'));
        $menu = Menu::getMenu();
        $info = Info::getAvailableInfo("system");

        //Set active nav
        session()->put('active_nav', 'system');
        session()->put('active_subnav', 'notification');

        return view ('system.notification.index')
            ->with('notification', $notification)
            ->with('dictionary', $dictionary)
            ->with('info', $info)
            ->with('menu', $menu)
            ->with('greet',$greet);
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

<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Converter;

use App\Models\Notification;
use App\Models\Dictionary;
use App\Models\History;
use App\Models\Info;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserGroup;

class NotificationController extends Controller
{
    public function index()
    {
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
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $result = Notification::where('id', $id)->update([
            'notif_type' => $request->notif_type,
            'notif_body' => $request->notif_body,
            //'notif_send_to' => $request->notif_send_to,
            'is_pending' => $request->is_pending,
            'pending_until' => $pending_date,
            'updated_at' => date("Y-m-d H:i"),
            'updated_by' => $user_id
        ]);

        return redirect()->back()->with('success_message', 'Notification has been updated');
    }

    public function delete_notif($id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));
        
        $result = Notification::where('id', $id)->update([
            'deleted_at' => date("Y-m-d H:i"),
            'deleted_by' => $user_id
        ]);

        return redirect()->back()->with('success_message', "Notification has been deleted");
    }

    public function add_notif(Request $request){
        $data = new Request();
        $obj = [
            'history_type' => "notification",
            'history_body' => "Has sended a new notification"
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $user_id = Generator::getUserIdV2(session()->get('role_key'));
            $uuid = Generator::getUUID();
            $sended_at = null;
            $sended_by = null;
            $is_pending = 0;
            $pending_until = null;
            $context_id = null;

            if($request->send_to != "pending"){
                $sended_at = date("Y-m-d H:i");
                $sended_by = $user_id;
                if($request->send_to == "person"){
                    $users = json_decode($request->list_context);
                    $list_user_holder = [];

                    foreach($users as $us){
                        $result = User::selectRaw("id, CONCAT(first_name,' ',last_name) as full_name")
                            ->where('username', $us->username)->first();
                        $list_user_holder[] = [
                            "id" => $result->id,
                            "username" => $us->username,
                            "fullname" => $us->full_name
                        ];
                    }
                    $context_id = $list_user_holder;
                } else if($request->send_to == "grouping"){
                    $groups = json_decode($request->list_context);
                    $list_group_holder = [];

                    foreach($groups as $gs){
                        $result = UserGroup::selectRaw("users_groups.id, user_id, username, CONCAT(first_name,' ',last_name) as full_name")
                            ->join("groups_relations","groups_relations.group_id","=","users_groups.id")
                            ->join("users","users.id","=","groups_relations.user_id")
                            ->where("slug_name", $gs->slug)
                            ->get();
                        
                        $list_user_holder = [];
                        foreach($result as $rs){
                            $list_user_holder[] = [
                                "id" => $rs->user_id,
                                "username" => $rs->username,
                                "fullname" => $rs->full_name
                            ];
                        }

                        $list_group_holder[] = [
                            "id" => $result[0]->id,
                            "groupname" => $gs->groupName,
                            "user_list" => $list_user_holder
                        ];
                    }
                    $context_id = $list_group_holder;
                }
            } else {
                $is_pending = 1;
                if($request->active_date == true){
                    $pending_until = Converter::getFullDate($request->pending_date, $request->pending_type);
                }                
            }

            $obj_send_to = [[
                'send_to' => $request->send_to,
                'context_id' => $context_id,
                'status' => false //For now
            ]];

            $ntf = Notification::create([
                'id' => $uuid,
                'notif_type' => $request->notif_type, 
                'notif_body' => $request->notif_body, 
                'notif_send_to' => $obj_send_to, 
                'is_pending' => $is_pending, 
                'pending_until' => $pending_until,
                'created_at' => date("Y-m-d H:i"),
                'created_by' => $user_id,
                'sended_at' => $sended_at,
                'sended_by' => $sended_by,
                'updated_at' => null,
                'updated_by' => null,
                'deleted_at' => null,
                'deleted_by' => null
            ]);

            History::create([
                'id' => Generator::getUUID(),
                'history_type' => $data->history_type, 
                'context_id' => $ntf->id, 
                'history_body' => $data->history_body, 
                'history_send_to' => null,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            ]);

            return redirect()->back()->with('success_message', "Notification has sended"); 
        }
    }
}

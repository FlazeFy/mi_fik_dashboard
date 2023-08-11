<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use DateTime;
use DateTimeZone;

use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Converter;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

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
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($role == 1){
            if($user_id != null){
                //Required config
                $select_1 = "Notification";

                $notification = Notification::getAllNotification();
                $dictionary = Dictionary::getDictionaryByType($select_1);
                $dct_tag = Dictionary::getDictionaryByType("Tag");
                $menu = Menu::getMenu();
                $info = Info::getAvailableInfo("system");

                //Set active nav
                session()->put('active_nav', 'system');
                session()->put('active_subnav', 'notification');

                return view ('system.notification.index')
                    ->with('notification', $notification)
                    ->with('dictionary', $dictionary)
                    ->with('info', $info)
                    ->with('dct_tag', $dct_tag)
                    ->with('menu', $menu);
            } else {
                return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
            }
        } else {
            return view("errors.403");
        }
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

    public function add_notif(Request $request)
    {
        DB::beginTransaction();

        try{
            $data = new Request();
            if($request->has('id')){
                $obj = [
                    'history_type' => "notification",
                    'history_body' => "Has sended a new notification"
                ];
            } else {
                $obj = [
                    'history_type' => "notification",
                    'history_body' => "Has make draft of new notification"
                ];
            }
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
                $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
                $messaging = $factory->createMessaging();
                $success = 0;
                $failed = 0;

                if($request->send_to != "pending"){
                    $sended_at = date("Y-m-d H:i");
                    $sended_by = $user_id;
                    if($request->send_to == "person"){
                        $users = json_decode($request->list_context);
                        $list_user_holder = [];

                        foreach($users as $us){
                            $result = DB::table("users")
                                ->selectRaw("id, CONCAT(first_name,' ',COALESCE(last_name, '')) as full_name,firebase_fcm_token")
                                ->where('username', $us->username)->first();
                            $list_user_holder[] = [
                                "id" => $result->id,
                                "username" => $us->username,
                                "fullname" => $us->full_name
                            ];

                            if($result->firebase_fcm_token){
                                $validateRegister = $messaging->validateRegistrationTokens($result->firebase_fcm_token);

                                if($validateRegister['valid'] != null){
                                    $type = ucfirst(substr($request->notif_type, strpos($request->notif_type, "_") + 1));  
                                    $message = CloudMessage::withTarget('token', $result->firebase_fcm_token)
                                        ->withNotification(
                                            FireNotif::create($request->notif_body)
                                            ->withTitle($request->notif_title)
                                            ->withBody(strtoupper($type)." ".$request->notif_body)
                                        )
                                        ->withData([
                                            'slug' => null,
                                            'module' => 'announcement'
                                        ]);

                                    if($request->send_time != "now"){
                                        // Do something
                                        $response = $messaging->send($message);
                                    } else {
                                        $response = $messaging->send($message);
                                    }
                                    $success++;
                                } else {
                                    DB::table("users")
                                        ->where('id', $result->id)->update([
                                            "firebase_fcm_token" => null
                                    ]);
                                    $failed++;
                                }
                            } else {
                                $failed++;
                            }
                        }
                        $context_id = $list_user_holder;
                    } else if($request->send_to == "grouping"){
                        $groups = json_decode($request->list_context);
                        $list_group_holder = [];

                        foreach($groups as $gs){
                            $result = DB::table("users_groups")
                                ->selectRaw("users_groups.id, user_id, username, CONCAT(first_name,' ',COALESCE(last_name, '')) as full_name,firebase_fcm_token")
                                ->join("groups_relations","groups_relations.group_id","=","users_groups.id")
                                ->join("users","users.id","=","groups_relations.user_id")
                                ->where("slug_name", $gs->slug)
                                ->get();
                            
                            $list_user_holder = [];

                            if(count($result) > 0){
                                foreach($result as $rs){
                                    $list_user_holder[] = [
                                        "id" => $rs->user_id,
                                        "username" => $rs->username,
                                        "fullname" => $rs->full_name
                                    ];
    
                                    if($rs->firebase_fcm_token){
                                        $validateRegister = $messaging->validateRegistrationTokens($rs->firebase_fcm_token);
    
                                        if($validateRegister['valid'] != null){
                                            $type = ucfirst(substr($request->notif_type, strpos($request->notif_type, "_") + 1));  
                                            $message = CloudMessage::withTarget('token', $rs->firebase_fcm_token)
                                                ->withNotification(
                                                    FireNotif::create($request->notif_body)
                                                    ->withTitle($request->notif_title)
                                                    ->withBody(strtoupper($type)." ".$request->notif_body)
                                                )
                                                ->withData([
                                                    'slug' => null,
                                                    'module' => 'announcement'
                                                ]);
                                            
                                            if($request->send_time != "now"){
                                                // Do something
                                                $response = $messaging->send($message);
                                            } else {
                                                $response = $messaging->send($message);
                                            }
                                            $success++;
                                        } else {
                                            DB::table("users")
                                                ->where('id', $rs->user_id)->update([
                                                    "firebase_fcm_token" => null
                                            ]);
                                            $failed++;
                                        }
                                    } else {
                                        $failed++;
                                    }
                                }

                                $list_group_holder[] = [
                                    "id" => $result[0]->id,
                                    "groupname" => $gs->groupName,
                                    "user_list" => $list_user_holder
                                ];
                            } else {
                                $list_group_holder[] = [
                                    "id" => null,
                                    "groupname" => $gs->groupName,
                                    "user_list" => null
                                ];
                            }   
                        }
                        $context_id = $list_group_holder;
                    } else if($request->send_to == "role"){
                        $roles = json_decode($request->list_context);
                        $list_role_holder = [];
                        $list_user_holder = [];
                    
                        $result = DB::table("users")
                            ->selectRaw("id, username, CONCAT(first_name,' ',COALESCE(last_name, '')) as full_name,firebase_fcm_token,role");

                        $arr_roles = "";
                        $total = count($roles);
                        for($i = 0; $i < $total; $i++){
                            $end = "";
                            if($i != $total - 1){
                                $end = "|";
                            } 
                            $arr_roles .= $roles[$i]->slug_name.$end;
                        }
                        $based_role = "JSON_EXTRACT(role, '$[*].slug_name') REGEXP '(".$arr_roles.")'";

                        $result = $result->whereRaw($based_role)->get();
                        
                        if($result->count() > 0){
                            foreach($result as $rs){
                                if($rs->firebase_fcm_token){
                                    $validateRegister = $messaging->validateRegistrationTokens($rs->firebase_fcm_token);

                                    if($validateRegister['valid'] != null){
                                        $type = ucfirst(substr($request->notif_type, strpos($request->notif_type, "_") + 1));  
                                        $message = CloudMessage::withTarget('token', $rs->firebase_fcm_token)
                                            ->withNotification(
                                                FireNotif::create($request->notif_body)
                                                ->withTitle($request->notif_title)
                                                ->withBody(strtoupper($type)." ".$request->notif_body)
                                            )
                                            ->withData([
                                                'slug' => null,
                                                'module' => 'announcement'
                                            ]);
                                        
                                        if($request->send_time != "now"){
                                            // Do something
                                            $response = $messaging->send($message);
                                        } else {
                                            $response = $messaging->send($message);
                                        }
                                        $success++;
                                        $list_user_holder[] = [
                                            "id" => $rs->id,
                                            "username" => $rs->username,
                                            "fullname" => $rs->full_name
                                        ];
                                    } else {
                                        DB::table("users")
                                            ->where('id', $rs->id)->update([
                                                "firebase_fcm_token" => null
                                        ]);
                                        $failed++;
                                    }
                                } else {
                                    $failed++;
                                }
                            }
                        } 
                        $list_role_holder = [
                            "tag_list" => json_decode(json_encode($roles), false),
                            "user_list" => $list_user_holder // Check this performance
                        ];
                        $context_id = $list_role_holder;                    
                    } else if($request->send_to == "all"){
                        $users = DB::table("users")
                            ->select("id","firebase_fcm_token")
                            ->get();

                        foreach($users as $us){
                            if($us->firebase_fcm_token){
                                $validateRegister = $messaging->validateRegistrationTokens($us->firebase_fcm_token);

                                if($validateRegister['valid'] != null){
                                    $type = ucfirst(substr($request->notif_type, strpos($request->notif_type, "_") + 1));  
                                    $message = CloudMessage::withTarget('token', $us->firebase_fcm_token)
                                        ->withNotification(
                                            FireNotif::create($request->notif_body)
                                            ->withTitle($request->notif_title)
                                            ->withBody(strtoupper($type)." ".$request->notif_body)
                                        )
                                        ->withData([
                                            'slug' => null,
                                            'module' => 'announcement'
                                        ]);

                                    if($request->send_time != "now"){
                                        // Do something
                                        $response = $messaging->send($message);
                                    } else {
                                        $response = $messaging->send($message);
                                    }
                                    $success++;
                                } else {
                                    DB::table("users")
                                        ->where('id', $us->id)->update([
                                            "firebase_fcm_token" => null
                                    ]);
                                    $failed++;
                                }
                            } else {
                                $failed++;
                            }
                        }
                        $context_id = null;
                    }
                    $status = "Notification sended to ".$success." user and failed to ".$failed." user";
                } else {
                    $is_pending = 1;
                    $sended_at = null;
                    $sended_by = null;
                    $status = null;
                    if($request->active_date == true){
                        $pending_until = Converter::getFullDate($request->pending_date, $request->pending_type);
                    }                
                }

                $obj_send_to = [[
                    'send_to' => $request->send_to,
                    'context_id' => $context_id,
                    'status' => $status
                ]];

                if($request->has('id') && $request->id != null){
                    $sended_at = date("Y-m-d H:i");
                    $sended_by = $user_id;

                    DB::table("notifications")->where('id',$request->id)
                        ->update([
                            'notif_type' => $request->notif_type, 
                            'notif_title' => $request->notif_title, 
                            'notif_body' => $request->notif_body, 
                            'notif_send_to' => json_encode($obj_send_to), 
                            'is_pending' => 0, 
                            'pending_until' => $pending_until,
                            'sended_at' => $sended_at,
                            'sended_by' => $sended_by,
                    ]);
                } else {
                    DB::table("notifications")->insert([
                        'id' => $uuid,
                        'notif_type' => $request->notif_type, 
                        'notif_title' => $request->notif_title, 
                        'notif_body' => $request->notif_body, 
                        'notif_send_to' => json_encode($obj_send_to), 
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
                }

                DB::table("histories")->insert([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $uuid, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);

                DB::commit();

                if($is_pending == 0 || $request->has('id')){
                    return redirect()->back()->with('success_message', "Notification has sended"); 
                } else {
                    return redirect()->back()->with('success_message', "Notification has saved"); 
                }
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null));
        }
    }
}

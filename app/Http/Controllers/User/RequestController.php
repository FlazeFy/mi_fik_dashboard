<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Converter;
use App\Helpers\Validation;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

use App\Models\User;
use App\Models\UserRequest;
use App\Models\Menu;
use App\Models\Info;
use App\Models\History;
use App\Models\Dictionary;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($role == 1){
            if($user_id != null){
                $greet = Generator::getGreeting(date('h'));
                $dct_tag = Dictionary::getDictionaryByType("Tag");
                $info = Info::getAvailableInfo("user/request");
                $menu = Menu::getMenu();

                //Set active nav
                session()->put('active_nav', 'manageuser');
                session()->put('active_subnav', 'request');

                return view('user.request.index')
                    ->with('menu', $menu)
                    ->with('info', $info)
                    ->with('dct_tag', $dct_tag)
                    ->with('greet',$greet);
            } else {
                return redirect("/")->with('failed_message','Session lost, please sign in again');
            }
        } else {
            return view("errors.403");
        }
    }

    public function add_role_acc(Request $request)
    {
        $admin_id = Generator::getUserIdV2(session()->get('role_key'));
        $user_id = Generator::getUserId($request->username, 2); 
        $tag = Converter::getTag($request->user_role);
        $new_user = $request->is_new;

        if($new_user == 1){
            User::where('id', $user_id)->update([
                'role' => $tag,
                'updated_by' => $admin_id,
                'is_accepted' => 1,
                'accepted_by' => $admin_id,
                'updated_at' => date("Y-m-d H:i"),
                'accepted_at' => date("Y-m-d H:i")
            ]);

            return redirect()->back()->with('success_message', 'Assign role success & Access granted');
        } else {
            User::where('id', $user_id)->update([
                'role' => $tag,
                'updated_by' => $admin_id,
                'updated_at' => date("Y-m-d H:i")
            ]);

            return redirect()->back()->with('success_message', 'Assign role success');
        }
    }

    public function add_acc(Request $request)
    {
        $admin_id = Generator::getUserIdV2(session()->get('role_key'));
        $user_id = Generator::getUserId($request->username, 2); 

        User::where('id', $user_id)->update([
            'updated_by' => $admin_id,
            'is_accepted' => 1,
            'accepted_by' => $admin_id,
            'updated_at' => date("Y-m-d H:i"),
            'accepted_at' => date("Y-m-d H:i")
        ]);

        return redirect()->back()->with('success_message', 'Access granted');
    }

    public function add_suspend(Request $request)
    {
        $admin_id = Generator::getUserIdV2(session()->get('role_key'));
        $user_id = Generator::getUserId($request->username, 2); 

        User::where('id', $user_id)->update([
            'updated_by' => $admin_id,
            'is_accepted' => 0,
            'accepted_by' => $admin_id,
            'updated_at' => date("Y-m-d H:i"),
            'accepted_at' => date("Y-m-d H:i")
        ]);

        return redirect()->back()->with('success_message', 'Account suspended');
    }

    public function add_recover(Request $request)
    {
        $admin_id = Generator::getUserIdV2(session()->get('role_key'));
        $user_id = Generator::getUserId($request->username, 2); 

        User::where('id', $user_id)->update([
            'updated_by' => $admin_id,
            'is_accepted' => 1,
            'updated_at' => date("Y-m-d H:i")
        ]);

        return redirect()->back()->with('success_message', 'Account recovered');
    }

    public function reject_request_multi(Request $request)
    {
        DB::beginTransaction();

        try{
            $admin_id = Generator::getUserIdV2(session()->get('role_key'));
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $messaging = $factory->createMessaging();
            
            $data = new Request();
            $obj = [
                'history_type' => "request",
                'history_body' => "request has been rejected"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $listreq = json_decode($request->list_request, true);

                if($listreq !== null || json_last_error() === JSON_ERROR_NONE){
                    //$role_count = count($listreq);
                    $failed = 0;
                    $count = 0;

                    foreach ($listreq as $key => $val) {
                        $user_id = Generator::getUserId($val['username'], 2);

                        if($user_id != null){
                            DB::table("users_requests")->where('id',$val['id'])
                                ->whereNull('accepted_at')
                                ->whereNull('rejected_at')
                                ->whereNull('is_rejected')
                                ->update([
                                    'is_rejected' => 1,
                                    'rejected_at' => date("Y-m-d H:i:s"),
                                    'rejected_by' => $admin_id
                                ]);
            
                            DB::table("histories")->insert([
                                'id' => Generator::getUUID(),
                                'history_type' => strtolower($data->history_type), 
                                'context_id' => $val['id'], 
                                'history_body' => $data->history_body, 
                                'history_send_to' => $user_id,
                                'created_at' => date("Y-m-d H:i:s"),
                                'created_by' => $admin_id
                            ]);  

                            $user = User::getUserRole($user_id, 0);
                            $firebase_token = $user[0]['firebase_fcm_token'];

                            if($firebase_token){
                                $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                                if($validateRegister['valid'] != null){
                                    $notif_body = "your role's request has been rejected";
                                    $notif_title = "Hello ".$user[0]['username'].", you got an information";
                                    $message = CloudMessage::withTarget('token', $firebase_token)
                                        ->withNotification(
                                            FireNotif::create($notif_body)
                                            ->withTitle($notif_title)
                                            ->withBody("REJECTED"." ".$notif_body)
                                        )
                                        ->withData([
                                            'slug' => null,
                                            'module' => 'request'
                                        ]);
                                    $response = $messaging->send($message);
                                } else {
                                    DB::table("users")->where('id', $user_id)->update([
                                        "firebase_fcm_token" => null
                                    ]);
                                }
                            }

                            $count++;
                        } else {
                            $failed++;
                        }  
                    }

                    DB::commit();
                    if($failed == 0){
                        return redirect()->back()->with('success_message', $count.' request rejected');
                    } else {
                        return redirect()->back()->with('success_message', $count.' request rejected and '.$failed.' request failed to reject');
                    }
                } else {
                    return redirect()->back()->with('failed_message', 'invalid request list');
                }       
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Create content failed '.$e);
        }
    }

    public function accept_request_multi(Request $request)
    {
        DB::beginTransaction();

        try{
            $admin_id = Generator::getUserIdV2(session()->get('role_key'));
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $messaging = $factory->createMessaging();

            $data = new Request();
            $obj = [
                'history_type' => "request",
                'history_body' => "request has been approve"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $listreq = json_decode($request->list_request, true);

                if($listreq !== null || json_last_error() === JSON_ERROR_NONE){
                    $failed = 0;
                    $count = 0;

                    foreach ($listreq as $key => $val) {
                        $status = false;
                        $user_id = Generator::getUserId($val['username'], 2);

                        if($val['request_type'] == "add"){
                            if($user_id != null){
                                $newRoles = json_decode($val['tag_list'], true);

                                if($newRoles !== null || json_last_error() === JSON_ERROR_NONE){
                                    $rolesOld = User::getUserRole($user_id, 0);
                                    if($rolesOld[0]['role']){
                                        $arr_role = $rolesOld[0]['role'];
                                    } else {
                                        $arr_role = [];
                                    }
                                    
                                    //Bug if we use formal looping
                                    $merge = array_merge($newRoles, $arr_role);
                                    $unique = array_map("unserialize", array_unique(array_map("serialize", $merge)));
                                    $newRoles = json_encode(array_values($unique));

                                    $status = true; 
                                } else {
                                    $status = false;
                                    $failed++;
                                } 
                            } else {
                                $status = false;
                                $failed++;
                            }  
                        } else if($val['request_type'] == "remove"){
                            if($user_id != null){
                                $newRoles = json_decode($val['tag_list'], true);

                                if($newRoles !== null || json_last_error() === JSON_ERROR_NONE){
                                    $rolesOld = User::getUserRole($user_id, 0);
                                    $uniqueKeys = [];
                                    $merge = array_merge($newRoles, $rolesOld[0]['role']);

                                    foreach ($merge as $mg) {
                                        $key = $mg['slug_name'];
                                        if (!in_array($key, $uniqueKeys)) {
                                            $unique[] = $mg;
                                            $uniqueKeys[] = $key;
                                        } else {
                                            $unique = array_filter($unique, 
                                            function($val) use ($key) {
                                                return $val['slug_name'] !== $key;
                                            });
                                        }
                                    }

                                    $newRoles = $unique;
                                    if(empty($newRoles)){
                                        $newRoles = null;
                                    } else {
                                        $newRoles = json_encode(array_values($newRoles));
                                    }
                                    $status = true; 
                                } else {
                                    $status = false;
                                    $failed++;
                                } 
                            } else {
                                $status = false;
                                $failed++;
                            }  
                        } else {
                            $status = false;
                            $failed++;
                        }

                        if($status){
                            DB::table("users")
                                ->where('id',$user_id)
                                ->update([
                                    'role' => $newRoles,
                                    'updated_at' => date("Y-m-d H:i:s"),
                                    'updated_by' => $admin_id
                            ]);

                            DB::table("users_requests")
                                ->where('id',$val['id'])
                                ->whereNull('accepted_at')
                                ->whereNull('rejected_at')
                                ->whereNull('is_rejected')
                                ->update([
                                    'is_accepted' => 1,
                                    'accepted_at' => date("Y-m-d H:i:s"),
                                    'accepted_by' => $admin_id
                                ]);
            
                            DB::table("histories")->insert([
                                'id' => Generator::getUUID(),
                                'history_type' => strtolower($data->history_type), 
                                'context_id' => $val['id'], 
                                'history_body' => $data->history_body, 
                                'history_send_to' => $user_id,
                                'created_at' => date("Y-m-d H:i:s"),
                                'created_by' => $admin_id
                            ]); 

                            $firebase_token = $rolesOld[0]['firebase_fcm_token'];
                            if($firebase_token){
                                $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                                if($validateRegister['valid'] != null){
                                    $notif_body = "your role has been updated";
                                    $notif_title = "Hello ".$rolesOld[0]['username'].", you got an information";
                                    $message = CloudMessage::withTarget('token', $firebase_token)
                                        ->withNotification(
                                            FireNotif::create($notif_body)
                                            ->withTitle($notif_title)
                                            ->withBody("APPROVED"." ".$notif_body)
                                        )
                                        ->withData([
                                            'slug' => null,
                                            'module' => 'user'
                                        ]);
                                    $response = $messaging->send($message);
                                } else {
                                    DB::table("users")
                                        ->where('id', $user_id)->update([
                                            "firebase_fcm_token" => null
                                    ]);
                                }
                            }
                        }
                        $count++;
                    }

                    DB::commit();
                    if($failed == 0){
                        return redirect()->back()->with('success_message', $count.' request approved');
                    } else {
                        return redirect()->back()->with('success_message', $count.' request aprroved and '.$failed.' request failed to approved');
                    }
                } else {
                    return redirect()->back()->with('failed_message', 'invalid request list');
                }       
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Create content failed '.$e);
        }
    }

    // Accept and reject new user request to join mifik should be integrated into one function. 
    // But later, make sure the flow is complete and correct first. LOL

    public function accept_join(Request $request, $isrole)
    {
        DB::beginTransaction();

        try{
            $admin_id = Generator::getUserIdV2(session()->get('role_key'));
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $messaging = $factory->createMessaging();

            $data = new Request();
            $obj = [
                'history_type' => "request",
                'history_body' => "request has been approve"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $listreq = json_decode($request->list_request, true);

                if($listreq !== null || json_last_error() === JSON_ERROR_NONE){
                    $failed = 0;
                    $count = 0;

                    foreach ($listreq as $key => $val) {
                        $status = false;
                        $user_id = DB::table("users")
                            ->select('id','firebase_fcm_token','username','password')
                            ->where('username',$val['username'])
                            ->first();

                        if($user_id != null){
                            if($isrole){
                                $role = Converter::getTag($request->role);
                                $notif_module = "home";
                                
                                DB::table("users")->
                                    where("id",$user_id->id)->update([
                                        'role' => $role,
                                        'accepted_at' => date("Y-m-d H:i:s"),
                                        'accepted_by' => $admin_id,
                                        'is_accepted' => 1
                                ]);
                            } else {
                                $notif_module = "landing";

                                DB::table("users")
                                    ->where("id",$user_id->id)->update([
                                        'accepted_at' => date("Y-m-d H:i:s"),
                                        'accepted_by' => $admin_id,
                                        'is_accepted' => 1
                                ]);
                            }

                            $status = true; 
                        } else {
                            $status = false;
                            $failed++;
                        }  
                        

                        if($status){
                            DB::table("histories")->insert([
                                'id' => Generator::getUUID(),
                                'history_type' => $data->history_type, 
                                'context_id' => null, 
                                'history_body' => $data->history_body, 
                                'history_send_to' => $user_id->id,
                                'created_at' => date("Y-m-d H:i:s"),
                                'created_by' => $admin_id
                            ]); 

                            $firebase_token = $user_id->firebase_fcm_token;
                            if($firebase_token){
                                $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                                if($validateRegister['valid'] != null){
                                    $notif_body = "your account has been validated by admin, explore Mi-FIK now!";
                                    $notif_title = "Hello ".$val['username'].", you got an information";
                                    $message = CloudMessage::withTarget('token', $firebase_token)
                                        ->withNotification(
                                            FireNotif::create($notif_body)
                                            ->withTitle($notif_title)
                                            ->withBody("APPROVED"." ".$notif_body)
                                        )
                                        ->withData([
                                            'slug' => null,
                                            'module' => $notif_module,
                                            'username' => $user_id->username,
                                            'password' => $user_id->password
                                        ]);
                                    $response = $messaging->send($message);
                                } else {
                                    DB::table("users")
                                        ->where('id', $user_id)->update([
                                        "firebase_fcm_token" => null
                                    ]);
                                }
                            }
                        }
                        $count++;
                    }

                    DB::commit();
                    if($failed == 0){
                        return redirect()->back()->with('success_message', $count.' new user approved');
                    } else {
                        return redirect()->back()->with('success_message', $count.' new user aprroved and '.$failed.' request failed to approved');
                    }
                } else {
                    return redirect()->back()->with('failed_message', 'invalid request list');
                }       
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Create content failed '.$e);
        }
    }

    public function reject_join(Request $request)
    {
        DB::beginTransaction();

        try{
            $admin_id = Generator::getUserIdV2(session()->get('role_key'));
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $messaging = $factory->createMessaging();

            $data = new Request();
            $obj = [
                'history_type' => "request",
                'history_body' => "request has been reject"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $listreq = json_decode($request->list_request, true);

                if($listreq !== null || json_last_error() === JSON_ERROR_NONE){
                    $failed = 0;
                    $count = 0;

                    foreach ($listreq as $key => $val) {
                        $status = false;
                        $user_id = DB::table("users")
                            ->select('id','firebase_fcm_token')
                            ->where('username',$val['username'])
                            ->first();

                        if($user_id != null){
                            DB::table("users")
                                ->where("id",$user_id->id)->update([
                                    'deleted_at' => date("Y-m-d H:i:s"),
                                    'deleted_by' => $admin_id,
                            ]);

                            $status = true; 
                        } else {
                            $status = false;
                            $failed++;
                        }  
                        

                        if($status){
                            DB::table("histories")->insert([
                                'id' => Generator::getUUID(),
                                'history_type' => strtolower($data->history_type), 
                                'context_id' => null, 
                                'history_body' => $data->history_body, 
                                'history_send_to' => $user_id->id,
                                'created_at' => date("Y-m-d H:i:s"),
                                'created_by' => $admin_id
                            ]); 

                            $firebase_token = $user_id->firebase_fcm_token;
                            if($firebase_token){
                                $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                                if($validateRegister['valid'] != null){
                                    $notif_body = "sorry, but we cant give you access to Mi-FIK due to some regulation";
                                    $notif_title = "Hello ".$val['username'].", you got an information";
                                    $message = CloudMessage::withTarget('token', $firebase_token)
                                        ->withNotification(
                                            FireNotif::create($notif_body)
                                            ->withTitle($notif_title)
                                            ->withBody("APPROVED"." ".$notif_body)
                                        )
                                        ->withData([
                                            'slug' => null,
                                            'module' => 'landing'
                                        ]);
                                    $response = $messaging->send($message);
                                } else {
                                    DB::table("users")
                                        ->where('id', $user_id)->update([
                                        "firebase_fcm_token" => null
                                    ]);
                                }
                            }
                        }
                        $count++;
                    }

                    DB::commit();
                    if($failed == 0){
                        return redirect()->back()->with('success_message', $count.' new user rejected');
                    } else {
                        return redirect()->back()->with('success_message', $count.' new user rejected and '.$failed.' new user failed to rejected');
                    }
                } else {
                    return redirect()->back()->with('failed_message', 'invalid request list');
                }       
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Create content failed '.$e);
        }
    }
}

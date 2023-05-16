<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Converter;
use App\Helpers\Validation;

use App\Models\User;
use App\Models\UserRequest;
use App\Models\Menu;
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
        $greet = Generator::getGreeting(date('h'));
        $dct_tag = Dictionary::getDictionaryByType("Tag");
        $menu = Menu::getMenu();

        //Set active nav
        session()->put('active_nav', 'manageuser');
        session()->put('active_subnav', 'request');

        return view('user.request.index')
            ->with('menu', $menu)
            ->with('dct_tag', $dct_tag)
            ->with('greet',$greet);
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
        $admin_id = Generator::getUserIdV2(session()->get('role_key'));
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
                        UserRequest::where('id',$val['id'])
                            ->whereNull('accepted_at')
                            ->whereNull('rejected_at')
                            ->whereNull('is_rejected')
                            ->update([
                                'is_rejected' => 1,
                                'rejected_at' => date("Y-m-d H:i:s"),
                                'rejected_by' => $admin_id
                            ]);
        
                        History::create([
                            'id' => Generator::getUUID(),
                            'history_type' => strtolower($data->history_type), 
                            'context_id' => $val['id'], 
                            'history_body' => $data->history_body, 
                            'history_send_to' => $user_id,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $admin_id
                        ]);  

                        $count++;
                    } else {
                        $failed++;
                    }  
                }

                if($failed == 0){
                    return redirect()->back()->with('success_message', $count.' request rejected');
                } else {
                    return redirect()->back()->with('success_message', $count.' request rejected and '.$failed.' request failed to reject');
                }
            } else {
                return redirect()->back()->with('failed_message', 'invalid request list');
            }       
        }
    }

    public function accept_request_multi(Request $request)
    {
        $admin_id = Generator::getUserIdV2(session()->get('role_key'));
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
                                
                                //Bug if we use formal looping
                                $merge = array_merge($newRoles, $rolesOld[0]['role']);
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
                        User::where('id',$user_id)
                            ->update([
                                'role' => $newRoles,
                                'updated_at' => date("Y-m-d H:i:s"),
                                'updated_by' => $admin_id
                        ]);

                        UserRequest::where('id',$val['id'])
                            ->whereNull('accepted_at')
                            ->whereNull('rejected_at')
                            ->whereNull('is_rejected')
                            ->update([
                                'is_accepted' => 1,
                                'accepted_at' => date("Y-m-d H:i:s"),
                                'accepted_by' => $admin_id
                            ]);
        
                        History::create([
                            'id' => Generator::getUUID(),
                            'history_type' => strtolower($data->history_type), 
                            'context_id' => $val['id'], 
                            'history_body' => $data->history_body, 
                            'history_send_to' => $user_id,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $admin_id
                        ]); 
                    }
                    $count++;
                }

                if($failed == 0){
                    return redirect()->back()->with('success_message', $count.' request approved');
                } else {
                    return redirect()->back()->with('success_message', $count.' request aprroved and '.$failed.' request failed to approve');
                }
            } else {
                return redirect()->back()->with('failed_message', 'invalid request list');
            }       
        }
    }
}

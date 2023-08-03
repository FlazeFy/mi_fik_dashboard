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
use App\Models\UserGroup;
use App\Models\GroupRelation;
use App\Models\Menu;
use App\Models\Info;
use App\Models\History;

class GroupingController extends Controller
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
                $info = Info::getAvailableInfo("user/group");
                $menu = Menu::getMenu();

                //Set active nav
                session()->put('active_nav', 'manageuser');
                session()->put('active_subnav', 'grouping');

                return view('user.group.index')
                    ->with('menu', $menu)
                    ->with('info', $info);
            } else {
                return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
            }
        } else {
            return view("errors.403");
        }
    }

    public function add_group(Request $request)
    {
        DB::beginTransaction();

        try{
            //Validate name avaiability
            $check = DB::table("users_groups")->where('group_name', $request->group_name)->get();

            if(count($check) == 0 && strtolower(str_replace(" ","", $request->group_name)) != "all"){
                $user_id = Generator::getUserIdV2(session()->get('role_key'));

                $validator = Validation::getValidateGroup($request);
                if ($validator->fails()) {
                    $errors = $validator->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    $data = new Request();
                    $obj = [
                        'history_type' => "group",
                        'history_body' => "Has created a group category called '".$request->dct_name."'"
                    ];
                    $data->merge($obj);

                    $validatorHistory = Validation::getValidateHistory($data);
                    if ($validatorHistory->fails()) {
                        $errors = $validatorHistory->messages();

                        return redirect()->back()->with('failed_message', $errors);
                    } else {
                        $slug = Generator::getSlugName($request->group_name, "group");
                        $uuid = Generator::getUUID();

                        $header = DB::table("users_groups")->insert([
                            'id' => $uuid,
                            'slug_name' => $slug,
                            'group_name' => $request->group_name,
                            'group_desc' => $request->group_desc,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id,
                            'updated_at' => null,
                            'updated_by' => null,
                            'deleted_at' => null,
                            'deleted_by' => null,
                        ]);

                        // if(is_countable($request->username)){
                        //     $user_count = count($request->username);
                        
                        //     for($i = 0; $i < $user_count; $i++){
                        //         $user_id_mng = Generator::getUserId($request->username[$i], 2);

                        //         GroupRelation::create([
                        //             'id' => Generator::getUUID(),
                        //             'group_id' => $uuid,
                        //             'user_id' => $user_id_mng,
                        //             'created_at' => date("Y-m-d H:i:s"),
                        //             'created_by' => $user_id,
                        //         ]);
                        //     }
                        // } 

                        if($request->has('selected_user')){
                            $users  = json_decode($request->selected_user, true);

                            if($users != null && json_last_error() === JSON_ERROR_NONE){

                                $count = count($users);
                                for($i = 0; $i < $count; $i++){
                                    $member_id = Generator::getUserId($users[$i]['username'], 2);

                                    DB::table("groups_relations")->insert([
                                        'id' => Generator::getUUID(),
                                        'group_id' => $uuid,
                                        'user_id' => $member_id,
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'created_by' => $user_id,
                                    ]);
                                }

                                $group_only = false;
                            } else {
                                $group_only = true;
                            }
                        } else {
                            $group_only = true;
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
                        if($group_only == true){
                            return redirect()->back()->with('success_message', "'".$request->group_name."' group has been created");
                        } else {
                            return redirect()->back()->with('success_message', "'".$request->group_name."' group has been created with ".$count." member");
                        }
                    }
                }
            } else {
                return redirect()->back()->with('failed_message', 'Please use unique name');
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Create group failed '.$e);
        }
    }

    public function delete_group(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $data = new Request();
        $obj = [
            'history_type' => "group",
            'history_body' => "Has deleted '".$request->dct_name."' group"
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            UserGroup::where('id',$id)->update([
                'deleted_at' => date("Y-m-d H:i:s"),
                'deleted_by' => $user_id
            ]);

            History::create([
                'id' => Generator::getUUID(),
                'history_type' => $data->history_type, 
                'context_id' => $id, 
                'history_body' => $data->history_body, 
                'history_send_to' => null,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            ]);

            return redirect()->back()->with('success_message', "'".$request->group_name."' group has been deleted");
        }
    }

    public function edit_group(Request $request, $id)
    {
        //Validate name avaiability
        $check = UserGroup::where('group_name', $request->group_name)->get();

        if((count($check) == 0 && strtolower(str_replace(" ","", $request->group_name)) != "all") || $request->group_name == $request->old_group_name){
            $user_id = Generator::getUserIdV2(session()->get('role_key'));

            $validator = Validation::getValidateGroup($request);
            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "group",
                    'history_body' => "Has updated '".$request->group_name."' group"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    $header = UserGroup::where('id',$id)->update([
                        'group_name' => $request->group_name,
                        'group_desc' => $request->group_desc,
                        'updated_at' => date("Y-m-d H:i:s"),
                        'updated_by' => $user_id
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);

                    return redirect()->back()->with('success_message', "'".$request->group_name."' group has been updated");
                }
            }
        } else {
            return redirect()->back()->with('failed_message', 'Please use unique name');
        }
    }

    public function add_member(Request $request, $id)
    {
        $data = new Request();
        $obj = [
            'history_type' => "group",
            'history_body' => "Has add some member to '".$request->group_name."'"
        ];
        $data->merge($obj);

        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $users  = json_decode($request->selected_member, true);

            if($users != null && json_last_error() === JSON_ERROR_NONE){

                $count = count($users);
                for($i = 0; $i < $count; $i++){
                    $member_id = Generator::getUserId($users[$i]['username'], 2);

                    GroupRelation::create([
                        'id' => Generator::getUUID(),
                        'group_id' => $id,
                        'user_id' => $member_id,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id,
                    ]);
                }

                $group_only = false;
            } else {
                $group_only = true;
            }

            History::create([
                'id' => Generator::getUUID(),
                'history_type' => $data->history_type, 
                'context_id' => $id, 
                'history_body' => $data->history_body, 
                'history_send_to' => null,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            ]);

            return redirect()->back()->with('success_message', "Some member has added to '".$request->group_name."' group");
        }
    }

    public function remove_member(Request $request, $id)
    {
        $data = new Request();
        $obj = [
            'history_type' => "group",
            'history_body' => "Has remove some member from '".$request->group_name."'"
        ];
        $data->merge($obj);

        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $users  = json_decode($request->selected_member_remove, true);

            if($users != null && json_last_error() === JSON_ERROR_NONE){

                $count = count($users);
                for($i = 0; $i < $count; $i++){
                    $rel_id = $users[$i]['id_rel'];

                    GroupRelation::destroy($rel_id);
                }

                $group_only = false;
            } else {
                $group_only = true;
            }

            History::create([
                'id' => Generator::getUUID(),
                'history_type' => $data->history_type, 
                'context_id' => $id, 
                'history_body' => $data->history_body, 
                'history_send_to' => null,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            ]);

            return redirect()->back()->with('success_message', "Some member has removed from '".$request->group_name."' group");
        }
    }

    public function set_ordering_content($order, $type)
    {
        if($type == "group_name"){
            $res = "group_name__".$order;
        } else if($type == "group_desc"){
            $res = "group_desc__".$order;
        } else if($type == "total"){
            $res = "total__".$order;
        } else if($type == "created_at"){
            $res = "created_at__".$order;
        }
        session()->put('ordering_group_list', $res);

        return redirect()->back()->with('success_message', 'Content ordered');
    }
}

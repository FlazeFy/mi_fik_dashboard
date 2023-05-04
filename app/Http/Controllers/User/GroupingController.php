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
        $greet = Generator::getGreeting(date('h'));
        $info = Info::getAvailableInfo("user/group");
        $menu = Menu::getMenu();

        //Set active nav
        session()->put('active_nav', 'manageuser');
        session()->put('active_subnav', 'grouping');

        return view('user.group.index')
            ->with('menu', $menu)
            ->with('info', $info)
            ->with('greet',$greet);
    }

    public function add_group(Request $request)
    {
        //Validate name avaiability
        $check = UserGroup::where('group_name', $request->group_name)->get();

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

                    $header = UserGroup::create([
                        'id' => $uuid,
                        'slug_name' => $slug,
                        'group_name' => $request->group_name,
                        'group_desc' => $request->group_desc,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'updated_by' => null,
                    ]);

                    // if(is_countable($request->username)){
                    //     $user_count = count($request->username);
                    
                    //     for($i = 0; $i < $user_count; $i++){
                    //         $user_id_mng = Generator::getUserId($request->username[$i], 2);

                    //         GroupRelation::create([
                    //             'id' => Generator::getUUID(),
                    //             'group_id' => $uuid,
                    //             'user_id' => $user_id_mng,
                    //             'created_at' => date("Y-m-d h:i:s"),
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

                                GroupRelation::create([
                                    'id' => Generator::getUUID(),
                                    'group_id' => $uuid,
                                    'user_id' => $member_id,
                                    'created_at' => date("Y-m-d h:i:s"),
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

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $header->id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);

                    if($group_only == true){
                        return redirect()->back()->with('success_message', "'".$request->group_name."' group has been created");
                    } else {
                        return redirect()->back()->with('success_message', "'".$request->group_name."' group has been created with ".$count." member");
                    }
                }
            }
        } else {
            return redirect()->back()->with('failed_message', 'Create group failed. Please use unique name');
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
                'deleted_at' => date("Y-m-d h:i:s"),
                'deleted_by' => $user_id
            ]);

            History::create([
                'id' => Generator::getUUID(),
                'history_type' => $data->history_type, 
                'context_id' => $id, 
                'history_body' => $data->history_body, 
                'history_send_to' => null,
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => $user_id
            ]);

            return redirect()->back()->with('success_message', "'".$request->group_name."' group has been deleted");
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

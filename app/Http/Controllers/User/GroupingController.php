<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Converter;

use App\Models\User;
use App\Models\UserGroup;
use App\Models\GroupRelation;
use App\Models\Menu;
use App\Models\Info;

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
        $info = Info::getAvailableInfo("user");
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
            $uuid = Generator::getUUID();

            $header = UserGroup::create([
                'id' => $uuid,
                'group_name' => $request->group_name,
                'group_desc' => $request->group_desc,
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => $user_id,
                'updated_at' => null,
                'updated_by' => null,
            ]);

            if(is_countable($request->slug_name)){
                $user_count = count($request->slug_name);
            
                for($i = 0; $i < $user_count; $i++){
                    $user_id_mng = Generator::getUserId($request->slug_name[$i], 2);

                    GroupRelation::create([
                        'id' => Generator::getUUID(),
                        'group_id' => $uuid,
                        'user_id' => $user_id_mng,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id,
                    ]);
                }
            } 

            return redirect()->back()->with('success_message', "'".$request->group_name."' group has been created");
        } else {
            return redirect()->back()->with('failed_message', 'Create group failed. Please use unique name');
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

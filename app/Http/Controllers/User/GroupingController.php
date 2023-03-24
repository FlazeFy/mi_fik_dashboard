<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Converter;

use App\Models\User;

class GroupingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
            $greet = Generator::getGreeting(date('h'));

            //Set active nav
            session()->put('active_nav', 'user');

            return view('user.group.index')
                ->with('greet',$greet);
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
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

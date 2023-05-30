<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Converter;

use App\Models\User;
use App\Models\Menu;
use App\Models\Dictionary;

class AllController extends Controller
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

        if($user_id != null){
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();
            $dct_tag = Dictionary::getDictionaryByType("Tag");

            //Set active nav
            session()->put('active_nav', 'manageuser');
            session()->put('active_subnav', 'all user');

            return view('user.all.index')
                ->with('menu', $menu)
                ->with('dct_tag', $dct_tag)
                ->with('greet',$greet);
        } else {
            return redirect("/")->with('failed_message','Session lost, try to sign in again');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_filter_name(Request $request, $all, $type)
    {
        if($all == 0){
            $filter = $request->filter_alph;
        } else {
            $filter = "all";
        }
        
        if($type == "front"){
            session()->put('filtering_fname', $filter);
        } else if($type == "last"){
            session()->put('filtering_lname', $filter);
        } else {
            session()->put('filtering_fname', "all");
            session()->put('filtering_lname', "all");
        }

        return redirect()->back()->with('success_message', 'Content filtered');
    }

    public function set_ordering_content($order, $type)
    {
        if($type == "username"){
            $res = "username__".$order;
        } else if($type == "email"){
            $res = "email__".$order;
        } else if($type == "first_name"){
            $res = "first_name__".$order;
        } else if($type == "joined"){
            $res = "created_at__".$order;
        }
        session()->put('ordering_user_list', $res);

        return redirect()->back()->with('success_message', 'Content ordered');
    }
}

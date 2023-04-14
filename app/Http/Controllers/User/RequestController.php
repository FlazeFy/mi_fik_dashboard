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

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        $greet = Generator::getGreeting(date('h'));
        $menu = Menu::getMenu();

        //Set active nav
        session()->put('active_nav', 'manageuser');
        session()->put('active_subnav', 'request');

        return view('user.request.index')
            ->with('menu', $menu)
            ->with('greet',$greet);
    }

    public function add_role_acc(Request $request)
    {
        //Helpers
        if(session()->get('slug_key')){
            $admin_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        }
        $user_id = Generator::getUserId($request->slug_user, 2); 
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
        //Helpers
        if(session()->get('slug_key')){
            $admin_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        }

        $user_id = Generator::getUserId($request->slug_user, 2); 

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
        //Helpers
        if(session()->get('slug_key')){
            $admin_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        }

        $user_id = Generator::getUserId($request->slug_user, 2); 

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
        //Helpers
        if(session()->get('slug_key')){
            $admin_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        }

        $user_id = Generator::getUserId($request->slug_user, 2); 

        User::where('id', $user_id)->update([
            'updated_by' => $admin_id,
            'is_accepted' => 1,
            'updated_at' => date("Y-m-d H:i")
        ]);

        return redirect()->back()->with('success_message', 'Account recovered');
    }
}

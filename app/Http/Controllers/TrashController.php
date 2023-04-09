<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Converter;
use App\Helpers\Generator;

use App\Models\Setting;
use App\Models\Menu;

class TrashController extends Controller
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
            $menu = Menu::getMenu();
            
            //Set active nav
            session()->put('active_nav', 'trash');

            return view ('trash.index')
                ->with('menu', $menu)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function set_ordering_content($order)
    {
        session()->put('ordering_trash', $order);

        return redirect()->back()->with('success_message', 'Content ordered');
    }
}

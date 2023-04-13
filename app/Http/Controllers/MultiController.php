<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MultiController extends Controller
{
    public function sort_section(Request $request, $menu, $navigation)
    {
        $active = $request->section;
        $body_menu = json_decode($request->menu);

        $i = array_search($active, $body_menu);
        array_splice($body_menu, $i, 1);

        if($navigation == "up"){
            array_splice($body_menu, $i - 1, 0, $active);
        } else if($navigation == "down"){
            array_splice($body_menu, $i + 1, 0, $active);
        }

        session()->put($menu.'_menu', $body_menu);

        return redirect()->back()->with('success_message', 'Section has sorted'); 
    }

    public function sign_out()
    {
        Session::flush();

        return redirect()->route('landing')->with('success_message', 'Successfully sign out'); 
    }
}

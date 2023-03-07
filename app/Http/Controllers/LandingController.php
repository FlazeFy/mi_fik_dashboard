<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\Admin;

class LandingController extends Controller
{
    public function index()
    {
        return view ('landing.index');
    }

    public function login_admin(Request $request){
        //Password hash
        //........

        $check = Admin::select('id','slug_name', 'username','image_url')
            ->where('username', $request->username)
            ->where('password', $request->password)
            ->limit(1)
            ->get();

        if(count($check) > 0){
            foreach($check as $c){
                $id = $c->id;
                $username = $c->username;
                $slug = $c->slug_name;
                $image = $c->image_url;
            }

            $request->session()->put('idKey', $id);
            $request->session()->put('usernameKey', $username);
            $request->session()->put('slugKey', $slug);
            $request->session()->put('profile_pic', $image);

            return redirect()->route('homepage');
        } else {
            return redirect()->back()->with('failed_message', 'Wrong username or password');
        }
    }
}

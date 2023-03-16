<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\Admin;
use App\Models\User;

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
                $username = $c->username;
                $slug = $c->slug_name;
                $image = $c->image_url;
            }

            $request->session()->put('username_key', $username);
            $request->session()->put('slug_key', $slug);
            $request->session()->put('profile_pic', $image);
            $request->session()->put('role', 0);

            return redirect()->route('homepage');
        } else {
            $check = User::select('id','slug_name', 'username','image_url')
                ->where('username', $request->username)
                ->where('password', $request->password)
                ->whereRaw("role like '%dosen%' or '%staff%'")
                ->limit(1)
                ->get();

            if(count($check) > 0){
                foreach($check as $c){
                    $username = $c->username;
                    $slug = $c->slug_name;
                    $image = $c->image_url;
                }
    
                $request->session()->put('username_key', $username);
                $request->session()->put('slug_key', $slug);
                $request->session()->put('profile_pic', $image);
                $request->session()->put('role', 1);
    
                return redirect()->route('homepage');
            } else {   
                return redirect()->back()->with('failed_message', 'Wrong username or password');
            }
        }
    }
}
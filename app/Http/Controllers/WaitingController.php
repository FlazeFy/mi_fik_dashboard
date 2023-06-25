<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;

use App\Models\Help;
use App\Models\Info;
use App\Models\Dictionary;
use App\Models\User;
use App\Models\UserRequest;

class WaitingController extends Controller
{
    public function index()
    {
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);
        $user = User::find($user_id);
        $found = false;

        if($user){
            if($user->role){
                foreach($user->role as $rl){
                    if($rl['slug_name'] == "lecturer" || $rl['slug_name'] == "staff"){
                        $found = true;
                        break;
                    }
                }

                if($found && $user->accepted_at){
                    return redirect()->route('homepage')->with('granted_message','Your registration process is completed. Welcome to Mi-FIK');
                }
            }
        } else {

        }

        if(!$role){
            $myreq = UserRequest::getRecentlyRequest($user_id);
            
            $ctc = Help::getAboutContact();
            $dct_tag = Dictionary::getDictionaryByType("Tag");
            $info = Info::getAvailableInfo("waiting");
            
            return view('waiting.index')
                ->with('info',$info)
                ->with('user', $user)
                ->with('dct_tag',$dct_tag)
                ->with('myreq', $myreq)
                ->with('ctc',$ctc);
        } else {
            return redirect()->route('homepage');
        }
    }
}

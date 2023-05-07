<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Helpers\Validation;
use App\Helpers\Generator;

use App\Models\Admin;
use App\Models\User;
use App\Models\Question;
use App\Models\Help;
use App\Models\Info;
use App\Models\Dictionary;
use App\Models\UserRequest;
use App\Models\Feedback;

class LandingController extends Controller
{
    public function index()
    {
        if(!session()->get('slug_key')){
            $faq = Question::getActiveFAQ();
            $ctc = Help::getAboutContact();
            $fbc = Feedback::getRandomFeedback();
            $info = Info::getAvailableInfo("landing");
            $dictionary = Dictionary::getDictionaryByType("Feedback");
            
            return view('landing.index')
                ->with('faq',$faq)
                ->with('fbc',$fbc)
                ->with('info',$info)
                ->with('dictionary',$dictionary)
                ->with('ctc',$ctc);
        } else {
            return redirect()->route('homepage');
        }
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

            return redirect()->route('homepage')->with('recatch_message', 'true');
        } else {
            $check = User::select('id','slug_name', 'username','image_url')
                ->where('username', $request->username)
                ->where('password', $request->password)
                ->whereRaw("role like '%lecturer%' or '%staff%'")
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

    public function login_auth(Request $request){
        $request->session()->put('username_key', $request->username);
        $request->session()->put('token_key', $request->token);
        $request->session()->put('role_key', $request->role);
        $request->session()->put('email_key', $request->email);
        $request->session()->put('profile_pic', $request->profile_pic);

        return redirect()->route('homepage')->with('recatch_message', 'true');
    }

    public function add_feedback(Request $request){
        $validator = Validation::getValidateFeedbackCreate($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            Feedback::create([
                'id' => Generator::getUUID(),
                'feedback_body' => $request->feedback_body,
                'feedback_rate' => $request->feedback_rate,
                'feedback_suggest' => $request->feedback_suggest,
                'created_at' => date("Y-m-d H:i"),
                'deleted_at' => null,
            ]);

            return redirect()->back()->with('success_message', 'Feedback has been sended');  
        }
    }
}

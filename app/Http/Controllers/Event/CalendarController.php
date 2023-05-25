<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;

use App\Models\ContentHeader;
use App\Models\Tag;
use App\Models\Menu;
use App\Models\User;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($user_id != null){
            if(!session()->get('selected_tag_calendar')){
                session()->put('selected_tag_calendar', "All");
            }

            $content = ContentHeader::getAllContentFilter(session()->get('selected_tag_calendar'), $role);       
            $tag = Tag::getFullTag("DESC", "DESC");
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();

            if($role == 1){
                $tag = Tag::getFullTag("DESC", "DESC");
                $mytag = null;
            } else {
                $tag = null;
                $user_id = Generator::getUserIdV2($role);
                $list = User::getUserRole($user_id, $role);
                foreach($list as $l){
                    $mytag = $l->role;
                }
            }
                
            //Set active nav
            session()->put('active_nav', 'event');
            session()->put('active_subnav', 'calendar');

            return view ('event.calendar.index')
                ->with('content', $content)
                ->with('tag', $tag)
                ->with('mytag', $mytag)
                ->with('menu', $menu)
                ->with('greet',$greet);
        } else {
            return redirect("/")->with('failed_message','Session lost, try to sign in again');
        }
    }

    public function set_filter_tag(Request $request, $all)
    {
        if($all == 0){
            $slug = $request->slug_name;
            if(is_array($slug)){
                $tag_count = count($slug);
                $tag_holder = [];

                for($i = 0; $i < $tag_count; $i++){
                    if($request->has('slug_name.'.$i)){
                        array_push($tag_holder, $slug[$i]);
                    } 
                }
            } else {
                $tag_holder = "All";
            }
        } else {
            $tag_holder = "All";
        }

        session()->put('selected_tag_calendar', $tag_holder);

        return redirect()->back()->with('success_message', 'Content filtered');
    }

    public function set_ordering_content($order)
    {
        session()->put('ordering_finished', $order);

        return redirect()->back()->with('success_message', 'Content ordered');
    }
}

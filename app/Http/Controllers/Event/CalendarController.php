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
                ->with('menu', $menu);
        } else {
            return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
        }
    }

    public function set_filter_tag(Request $request, $all, $from)
    {
        if($all == 0){
            $tag = $request->tag;
            if(is_array($tag)){
                $tag_count = count($tag);
                $tag_holder = [];

                for($i = 0; $i < $tag_count; $i++){
                    if($request->has('tag.'.$i)){
                        $tags = explode("__",$tag[$i]);
                        array_push($tag_holder, 
                            (object)['slug_name' => $tags[0], 'tag_name' => $tags[1]],
                        );
                    } 
                }
            } else {
                $tag_holder = "All";
            }
        } else {
            $tag_holder = "All";
        }

        session()->put('selected_tag_'.$from, $tag_holder);

        return redirect()->back()->with('success_mini_message', 'Content filtered');
    }

    public function set_ordering_content($order)
    {
        session()->put('ordering_finished', $order);

        return redirect()->back()->with('success_mini_message', 'Content ordered');
    }
}

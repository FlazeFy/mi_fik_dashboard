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

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
            
            if(!session()->get('selected_tag_calendar')){
                session()->put('selected_tag_calendar', "All");
            }

            $content = ContentHeader::getAllContentFilter(session()->get('selected_tag_calendar'));       
            $tag = Tag::getFullTag("DESC", "DESC");
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();
                
            //Set active nav
            session()->put('active_nav', 'event');
            session()->put('active_subnav', 'calendar');

            return view ('event.calendar.index')
                ->with('content', $content)
                ->with('tag', $tag)
                ->with('menu', $menu)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
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

    public function sort_section(Request $request, $navigation)
    {
        $active = $request->section;
        $calendar_menu = json_decode($request->menu);

        $i = array_search($active, $calendar_menu);
        array_splice($calendar_menu, $i, 1);

        if($navigation == "up"){
            array_splice($calendar_menu, $i - 1, 0, $active);
        } else if($navigation == "down"){
            array_splice($calendar_menu, $i + 1, 0, $active);
        }

        session()->put('calendar_menu', $calendar_menu);

        return redirect()->back()->with('success_message', 'Section has sorted'); 
    }
}

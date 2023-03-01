<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Models\ContentHeader;
use App\Models\Tag;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!session()->get('selected_tag_calendar')){
            session()->put('selected_tag_calendar', "All");
        }

        // $filter_tag = ["IF-Lab", "Tugas Akhir"]; //Can admin see all content?...

        //Fix this stupid code LOL
        //Should be use IN() instead of "WHERE & OR"
        if(session()->get('selected_tag_calendar') != "All"){
            $i = 1;
            $query = "";
            $filter_tag = session()->get('selected_tag_calendar');
            
            foreach($filter_tag as $ft){
                //$stmt = "JSON_EXTRACT(replace(replace(content_tag, '[', ''), ']', ''), '$.slug_name') = '".$ft."'";
                $stmt = 'content_tag like '."'".'%"slug_name":"'.$ft.'"%'."'";

                if($i != 1){
                    $query = substr_replace($query, " ".$stmt." OR", 0, 0);
                } else {
                    $query = substr_replace($query, " ".$stmt, 0, 0);
                }
                $i++;
            }

            $content = ContentHeader::select('slug_name','content_title','content_image','content_date_start','content_date_end','content_tag')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->whereRaw($query)
                ->get();

        } else {
            $content = ContentHeader::select('slug_name','content_title','content_image','content_date_start','content_date_end','content_tag')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->get();
        }
        
        $tag = Tag::all();
            
        //Set active nav
        session()->put('active_nav', 'event');

        return view ('event.calendar.index')
            ->with('content', $content)
            ->with('tag', $tag);
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
}

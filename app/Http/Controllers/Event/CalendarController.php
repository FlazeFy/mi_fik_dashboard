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
    public function index()
    {
        $slct_tag = ["IF-Lab", "Tugas Akhir"];
        session()->put('selected_tag_calendar', $slct_tag);

        $myrole = ["IF-Lab", "Tugas Akhir"]; //Can admin see all content?...
        $i = 1;
        $query = "";

        //Fix this stupid code LOL
        //Should be use IN() instead of "WHERE & OR"
        foreach($myrole as $mr){
            $stmt = "JSON_EXTRACT(replace(replace(content_tag, '[', ''), ']', ''), '$.tag_name') = '".$mr."'";
            if($i != 1){
                $query = substr_replace($query, " ".$stmt." OR", 0, 0);
            } else {
                $query = substr_replace($query, " ".$stmt, 0, 0);
            }
            $i++;
        }

        $content = ContentHeader::select('slug_name','content_title','content_desc','content_image','content_loc','content_date_start','content_date_end','content_tag','content_attach','contents_headers.created_at','contents_headers.updated_at')
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->whereRaw($query)
            ->get();
        
        $tag = Tag::all();
            
        //Set active nav
        session()->put('active_nav', 'event');

        return view ('event.calendar.index')
            ->with('content', $content)
            ->with('tag', $tag);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

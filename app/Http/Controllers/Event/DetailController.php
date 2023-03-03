<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Models\ContentHeader;
use App\Models\Tag;

class DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug_name)
    {
        $tag = Tag::orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        $content = ContentHeader::select('slug_name','content_title','content_desc','content_image','content_loc','content_date_start','content_date_end','content_tag','content_attach','contents_headers.created_at','contents_headers.updated_at')
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->where('slug_name', $slug_name)
            ->get();

        //Set active nav
        session()->put('active_nav', 'event');
        $title = $content[0]['content_title'];

        return view ('event.detail.index')
            ->with('tag', $tag)
            ->with('content', $content)
            ->with('title', $title);
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

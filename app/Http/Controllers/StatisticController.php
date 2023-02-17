<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\content;
use App\Models\Setting;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Chart query
        $mostTag = Content::select('content_tag')
            ->whereNot('content_tag', null)
            ->get();

        $mostLoc = Content::select('content_loc')
            ->whereNot('content_loc', null)
            ->get();
        
        //Set active nav
        session()->put('active_nav', 'statistic');

        $setting = Setting::select('id', 'MOT_range', 'MOL_range', 'CE_range')
            ->where('id_user', 1)
            ->get();

        foreach($setting as $set){
            $createdEvent = Content::selectRaw("MONTH(created_at) as 'month', COUNT(*) as total")
                ->where('created_at', '>=', date("Y-m-d", strtotime("-".$set->CE_range." months")))
                ->groupByRaw('MONTH(created_at)')
                ->get();
        }

        return view ('statistic.index')
            ->with('mostTag', $mostTag)
            ->with('mostLoc', $mostLoc)
            ->with('setting', $setting)
            ->with('createdEvent', $createdEvent);
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

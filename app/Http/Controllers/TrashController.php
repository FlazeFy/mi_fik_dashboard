<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Converter;
use App\Helpers\Generator;

use App\Models\Setting;
use App\Models\Menu;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
            $setting = Setting::getChartSetting($user_id);
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();
            
            //Set active nav
            session()->put('active_nav', 'trash');

            return view ('trash.index')
                ->with('setting', $setting)
                ->with('menu', $menu)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
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

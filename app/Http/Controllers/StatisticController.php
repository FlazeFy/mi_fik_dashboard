<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\ContentDetail;
use App\Models\ContentHeader;
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
        $setting = Setting::getChartSetting('dc4d52ec-afb1-11ed-afa1-0242ac120002');

        //Chart query
        $mostTag = ContentDetail::getMostUsedTag();
        $mostLoc = ContentDetail::getMostUsedLoc();
        foreach($setting as $set){
            $createdEvent = ContentHeader::getTotalContentByMonth($set->CE_range);
        }
        
        //Set active nav
        session()->put('active_nav', 'statistic');

        return view ('statistic.index')
            ->with('mostTag', $mostTag)
            ->with('mostLoc', $mostLoc)
            ->with('setting', $setting)
            ->with('createdEvent', $createdEvent);
    }

    public function update_mot(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'MOT_range' => $request->MOT_range,
            'updated_at' => date("Y-m-d h:i"),
        ]);

        return redirect()->back()->with('success_message', 'Chart range updated');
    }

    public function update_mol(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'MOL_range' => $request->MOL_range,
            'updated_at' => date("Y-m-d h:i"),
        ]);

        return redirect()->back()->with('success_message', 'Chart range updated');
    }

    public function update_ce(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'CE_range' => $request->CE_range,
            'updated_at' => date("Y-m-d h:i"),
        ]);

        return redirect()->back()->with('success_message', 'Chart range updated');
    }
}

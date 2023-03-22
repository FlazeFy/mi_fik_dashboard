<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Converter;
use App\Helpers\Generator;

use App\Models\Setting;

class SettingController extends Controller
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
            
            //Set active nav
            session()->put('active_nav', 'setting');

            return view ('setting.index')
                ->with('setting', $setting)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function update_chart(Request $request)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role')); 

        Setting::where('created_by', $user_id)->update([
            'MOT_range' => $request->MOT_range,
            'MOL_range' => $request->MOL_range,
            'CE_range' => $request->CE_range,
            'MVE_range' => $request->MVE_range,
            'updated_at' => date("Y-m-d H:i"),
        ]);

        return redirect()->back()->with('success_message', 'Setting updated');
    }
}

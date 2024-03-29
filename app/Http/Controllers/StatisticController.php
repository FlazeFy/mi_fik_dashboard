<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;

use App\Models\ContentDetail;
use App\Models\ContentHeader;
use App\Models\Setting;
use App\Models\User;
use App\Models\Menu;
use App\Models\Info;
use App\Models\Feedback;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($role == 1){
            if($user_id != null){
                $setting = Setting::getChartSetting($user_id);

                //Chart query
                $mostTag = ContentDetail::getMostUsedTag();
                $mostLoc = ContentDetail::getMostUsedLoc();
                $mostRole = User::getMostUsedRole();
                $menu = Menu::getMenu();
                $info = Info::getAvailableInfo("statistic");
                $suggestion = Feedback::getAllFeedbackSuggestion();

                foreach($setting as $set){
                    $createdEvent = ContentHeader::getTotalContentByMonth($set->CE_range);
                    //$mostViewed = ContentDetail::getMostViewedEvent($set->MVE_range);
                    $mostViewed = ContentDetail::getMostViewedEventSeparatedRole($set->MVE_range);
                }
                
                //Set active nav
                session()->put('active_nav', 'statistic');
                session()->forget('active_subnav');

                return view ('statistic.index')
                    ->with('mostTag', $mostTag)
                    ->with('mostLoc', $mostLoc)
                    ->with('mostRole', $mostRole)
                    ->with('mostViewed', $mostViewed)
                    ->with('setting', $setting)
                    ->with('menu', $menu)
                    ->with('info', $info)
                    ->with('suggestion', $suggestion)
                    ->with('createdEvent', $createdEvent);
            } else {
                return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
            }
        } else {
            return view("errors.403");
        }
    }

    public function update_mot(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'MOT_range' => $request->MOT_range,
            'updated_at' => date("Y-m-d H:i"),
        ]);

        return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("business_update",'chart range',null));
    }

    public function update_mol(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'MOL_range' => $request->MOL_range,
            'updated_at' => date("Y-m-d H:i"),
        ]);

        return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("business_update",'chart range',null));
    }

    public function update_ce(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'CE_range' => $request->CE_range,
            'updated_at' => date("Y-m-d H:i"),
        ]);

        return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("business_update",'chart range',null));
    }

    public function update_mve(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'MVE_range' => $request->MVE_range,
            'updated_at' => date("Y-m-d H:i"),
        ]);

        return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("business_update",'chart range',null));
    }

    public function update_mve_view(Request $request)
    {
        session()->put('selected_view_mve_chart', $request->MVE_view);

        return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("business_update",'chart view',null));
    }
}

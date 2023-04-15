<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Converter;
use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Setting;
use App\Models\History;
use App\Models\SettingSystem;
use App\Models\Menu;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Generator::getUserIdV2(1);
        $setting = Setting::getChartSetting($user_id);
        $settingJobs = SettingSystem::getJobsSetting();
        $greet = Generator::getGreeting(date('h'));
        $menu = Menu::getMenu();
        
        //Set active nav
        session()->put('active_nav', 'setting');
        session()->forget('active_subnav');

        return view ('setting.index')
            ->with('setting', $setting)
            ->with('settingJobs', $settingJobs)
            ->with('menu', $menu)
            ->with('greet',$greet);
    }

    public function update_chart(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        //Helpers
        $validator = Validation::getValidateSetting($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            Setting::where('created_by', $user_id)->update([
                'MOT_range' => $request->MOT_range,
                'MOL_range' => $request->MOL_range,
                'CE_range' => $request->CE_range,
                'MVE_range' => $request->MVE_range,
                'updated_at' => date("Y-m-d H:i"),
            ]);

            return redirect()->back()->with('success_message', 'Setting updated');
        }   

        return redirect()->back()->with('success_message', 'Setting updated');
    }

    public function update_jobs(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateJobs($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "scheduling",
                'history_body' => "Has changed days limit for deleted content"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                SettingSystem::where('id', $id)->update([
                    'DCD_range' => $request->DCD_range,
                    'DTD_range' => $request->DTD_range,
                    'updated_at' => date("Y-m-d H:i"),
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Setting updated');  
            }
        }        
    }
}

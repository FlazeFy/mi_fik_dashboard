<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Attendance;
use App\Models\Info;
use App\Models\History;
use App\Models\Menu;

class AttendanceDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($user_id != null){
            $menu = Menu::getMenu();

            $attendance = Attendance::getAttendanceDetail($id);
            $attresponse = Attendance::getAttendanceResponse($id);
            $info = Info::getAvailableInfo("attendance");

            //Set active nav
            session()->put('active_nav', 'attendance');
            session()->forget('active_subnav');

            return view ('attendance.detail.index')
                ->with('menu',$menu)
                ->with('info', $info)
                ->with('attd',$attendance)
                ->with('atrs',$attresponse);
        } else {
            return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
        }
    }

    public function toogle_edit_attendance($ctx, $switch)
    {
        session()->put('toogle_edit_'.$ctx, $switch);

        if($switch == "true"){
            return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("custom","You're in edit mode",null));
        } else {
            return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("custom","You're in view mode",null));
        }
    }

    public function update_attendance_info(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        if($id != null){
            $validator = Validation::getValidateAttendanceInfo($request);
            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "attendance",
                    'history_body' => "Has updated this attendance info"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    Attendance::where('id', $id)->update([
                        'attendance_title' => $request->attendance_title, 
                        'attendance_desc' => $request->attendance_desc, 
                        'updated_at' => date("Y-m-d H:i:s"),
                        'updated_by' => $user_id
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);
                }

                return redirect()->back()->with('success_message', "Attendance successfully updated");             
            }
        } else {
            return redirect()->back()->with('failed_message', "Attendance update is failed, the attendance doesn't exist anymore");   
        }
    }
}

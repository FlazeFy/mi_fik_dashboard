<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Generator;
use App\Models\Attendance;
use App\Models\Menu;

class AttendanceController extends Controller
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

        if($user_id != null){
            $menu = Menu::getMenu();

            $attendance = Attendance::getAllAttendanceHeaders();

            //Set active nav
            session()->put('active_nav', 'attendance');
            session()->forget('active_subnav');

            return view ('attendance.index')
                ->with('menu',$menu)
                ->with('attd',$attendance);
        } else {
            return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
        }
    }

}

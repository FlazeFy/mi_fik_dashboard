<?php

namespace App\Http\Controllers\Api\AttendanceApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Generator;

use App\Models\Attendance;

class Queries extends Controller
{
    public function getAllAttendance($limit){
        try{
            $atd = Attendance::select('attendance.id as id', 'contents_headers.content_slug', 'contents_headers.content_title', 'attendance_title', 'attendance_desc', 'is_view', 'attendance_time_start',
                    'attendance_time_end', 'created_at', 'users.username as created_by_user', 'admins.username as created_by_admin')
                ->leftjoin('contents_headers','contents_headers.id','=','attendance.content_id')
                ->leftjoin('admins', 'admins.id', '=', 'attendance.created_by')
                ->leftjoin('users', 'users.id', '=', 'attendance.created_by')
                ->orderBy('created_at', 'ASC')
                ->orderBy('attendance_title', 'DESC');

            $atd = $atd->paginate($limit);

            if ($atd->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'attendance', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'attendance', null),
                    'data' => $atd
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",$e,null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

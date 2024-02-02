<?php

namespace App\Http\Controllers\Api\AttendanceApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Generator;

use App\Models\Attendance;

class Queries extends Controller
{
    public function getAllAttendanceHeaders(){
        try{
            $atd = Attendance::getAllAttendanceHeaders();

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

<?php

namespace App\Http\Controllers\Api\AttendanceApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Validation;
use App\Helpers\Generator;

use App\Models\Attendance;
use App\Models\AttendanceRelation;
use App\Models\History;

class Commands extends Controller
{
    public function destroyAttendance(Request $request, $id)
    {
        try{
            $data = new Request();
            $obj = [
                'history_type' => "attendance",
                'history_body' => "Has deleted '".$request->attendance_title."' attendance"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $user_id = $request->user()->id;

                Attendance::destroy($id);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $id, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_delete", 'attendance', $request->attendance_title),
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",$e,null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function postAttendance(Request $request)
    {
        try{
            $data = new Request();
            $obj = [
                'history_type' => "attendance",
                'history_body' => "Has created '".$request->attendance_title."' attendance"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $user_id = $request->user()->id;
                $id = Generator::getUUID();

                AttendanceRelation::create([
                    'id' => $id,
                    'attendance_id' => $request->attendance_id,
                    'users_id' => $request->users_id,
                    'attendance_answer' => $request->attendance_answer,
                    'created_at' => date("Y-m-d H:i:s")
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

                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_create", 'attendance', $request->attendance_title),
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

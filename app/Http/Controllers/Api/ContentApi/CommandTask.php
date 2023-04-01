<?php

namespace App\Http\Controllers\Api\ContentApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Task;
use App\Models\ArchiveRelation;

use App\Helpers\Validation;

class CommandTask extends Controller
{
    public function updateTask(Request $request, $id){
        try{
            $validator = Validator::make($request->all(), [
                'task_title' => 'required',
                'task_desc' => 'required',
                'task_date_start' => 'required',
                'task_date_end' => 'required',
                'task_reminder' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $task = Task::where('id', $id)->update([
                    'task_title' => $request->task_title,
                    'task_desc' => $request->task_desc,
                    'task_date_start' => $request->task_date_start,
                    'task_date_end' => $request->task_date_end,
                    'task_reminder' => $request->task_reminder,
                    'updated_at' => date("Y-m-d h:i")
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Task Updated',
                    'data' => $task
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteTask(Request $request, $id){
        try{
            $task = Task::where('id', $id)->update([
                'deleted_at' => date("Y-m-d h:i:s"),
                'deleted_by' => $request->user_id,
            ]);
            ArchiveRelation::where('content_id',$id)
                ->where('created_by', $request->user_id)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Task Deleted',
                'data' => $task
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroyTask(Request $request, $id){
        try{
            $task = Task::destroy($id);
            ArchiveRelation::where('content_id',$id)
                ->where('created_by', $request->user_id)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Task Permentaly Deleted',
                'data' => $task
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addTask(){
        try{
            //Helpers
            $validator = Validation::getValidateTask($request);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 422,
                    'message' => 'Add task failed',
                    'error' => $errors
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $task = Task::create([
                    'task_title' => $request->task_title,
                    'task_desc' => $request->task_desc,
                    'task_date_start' => $request->task_date_start,
                    'task_date_end' => $request->task_date_end,
                    'task_reminder' => $request->task_reminder,
                    'created_at' => date("Y-m-d h:i"),
                    'created_by' => '1',
                    'updated_at' => null,
                    'updated_by' => null,
                    'deleted_at' => null,
                    'deleted_by' => null,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Task Updated',
                    'data' => $task
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

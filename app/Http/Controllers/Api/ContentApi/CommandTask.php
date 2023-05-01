<?php

namespace App\Http\Controllers\Api\ContentApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Task;
use App\Models\History;
use App\Models\ArchiveRelation;

use App\Helpers\Validation;
use App\Helpers\Generator;

class CommandTask extends Controller
{
    public function updateTask(Request $request, $id){
        try{
            $validator = Validation::getValidateTaskV2($request);
            $user_id = $request->user()->id;

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "task",
                    'history_body' => "has created a task called '".$request->task_title."'"
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
                    $task = Task::where('id', $id)->update([
                        'task_title' => $request->task_title,
                        'task_desc' => $request->task_desc,
                        'task_date_start' => $request->task_date_start,
                        'task_date_end' => $request->task_date_end,
                        'task_reminder' => $request->task_reminder,
                        'updated_at' => date("Y-m-d h:i"),
                        'updated_by' => $user_id
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Task Updated',
                        'data' => $task
                    ], Response::HTTP_OK);
                }
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
            $user_id = $request->user()->id;

            $data = new Request();
            $obj = [
                'history_type' => "task",
                'history_body' => "has deleted '".$request->task_title."' task"
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
                $task = Task::where('id', $id)->update([
                    'deleted_at' => date("Y-m-d h:i:s"),
                    'deleted_by' => $user_id,
                ]);
                
                ArchiveRelation::where('content_id',$id)
                    ->where('created_by', $user_id)
                    ->delete();

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $id, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Task Deleted',
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

    public function destroyTask(Request $request, $id){
        try{
            $user_id = $request->user()->id;

            $data = new Request();
            $obj = [
                'history_type' => "task",
                'history_body' => "has destroy '".$request->task_title."' task"
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
                $task = Task::destroy($id);

                ArchiveRelation::where('content_id',$id)
                    ->where('created_by', $user_id)
                    ->delete();
                
                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);  

                return response()->json([
                    'status' => 'success',
                    'message' => 'Task Permentaly Deleted',
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

    public function addTask(Request $request){
        try{
            //Helpers
            $validator = Validation::getValidateTaskV2($request);
            $user_id = $request->user()->id;

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "task",
                    'history_body' => "has created a task called '".$request->task_title."'"
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
                    $slug = Generator::getSlugName($request->task_title, "task");

                    $task = Task::create([
                        'id' => Generator::getUUID(),
                        'slug_name' => $slug,
                        'task_title' => $request->task_title,
                        'task_desc' => $request->task_desc,
                        'task_date_start' => $request->task_date_start,
                        'task_date_end' => $request->task_date_end,
                        'task_reminder' => $request->task_reminder,
                        'created_at' => date("Y-m-d h:i"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'updated_by' => null,
                        'deleted_at' => null,
                        'deleted_by' => null,
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $task->id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Task created',
                        'data' => $task
                    ], Response::HTTP_OK);
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

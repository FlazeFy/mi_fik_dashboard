<?php

namespace App\Http\Controllers\Api\ContentApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Task;

class QueryTask extends Controller
{
    public function getMyTask($user_id) {
        try{
            $archive = Task::select('slug_name','task_title','task_desc','task_date_start','task_date_end','task_reminder','created_at','updated_at')
                ->where('created_by', $user_id)
                ->orderBy('created_at', 'DESC')
                ->paginate(15);

            if ($archive->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Task Found',
                    'data' => $archive
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

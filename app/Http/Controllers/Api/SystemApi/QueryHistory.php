<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Models\History;

class QueryHistory extends Controller
{
    //
    public function getMyHistory(Request $request) {
        try {
            $user_id = $request->user()->id;

            $history = History::select('history_type', 'history_body', 'created_at')
                ->where('context_id', $user_id)
                ->orWhere('history_send_to', $user_id)
                ->orWhere('created_by', $user_id)
                ->orderBy('created_at', 'DESC')
                ->get();

            if($history->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'History Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'History Found',
                    'data' => $history
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

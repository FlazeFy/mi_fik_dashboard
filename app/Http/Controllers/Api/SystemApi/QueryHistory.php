<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\History;
use App\Helpers\Generator;

class QueryHistory extends Controller
{
    //
    public function getMyHistory(Request $request) {
        try {
            $user_id = $request->user()->id;

            $history = History::select('id','history_type', 'history_body', 'created_at')
                ->where('context_id', $user_id)
                ->orWhere('history_send_to', $user_id)
                ->orWhere('created_by', $user_id)
                ->orderBy('created_at', 'DESC')
                ->get();

            $clean = [];

            foreach($history as $hs){
                if($hs->history_type == "request"){
                    $body = "Your ".trim($hs->history_body);
                } else if($hs->history_type == "faq" || $hs->history_type == "task"){
                    $body = "You ".trim($hs->history_body);
                } else {
                    $body = trim($hs->history_body);
                }

                $clean[] = [
                    'id' => $hs->id,
                    'history_type' => ucfirst($hs->history_type),
                    'history_body' => ucfirst($body),
                    'created_at' => $hs->created_at
                ];
            }

            $collection = collect($clean);
            $collection = $collection->sortByDesc('created_at')->values();
            $perPage = 20;
            $page = request()->input('page', 1);
            $paginator = new LengthAwarePaginator(
                $collection->forPage($page, $perPage)->values(),
                $collection->count(),
                $perPage,
                $page,
                ['path' => url()->current()]
            );
            $clean = $paginator->appends(request()->except('page'));

            if(count($clean) == 0) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'history', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'history', null),
                    'data' => $clean
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

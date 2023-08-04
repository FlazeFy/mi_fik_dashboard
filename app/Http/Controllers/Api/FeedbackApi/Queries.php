<?php

namespace App\Http\Controllers\Api\FeedbackApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Feedback;

class Queries extends Controller
{
    public function getAllFeedbackSuggestionApi() {
        try {
            $queries = Feedback::selectRaw('dct_name as category, count(1) as total')
                ->join('dictionaries', 'dictionaries.slug_name', '=', 'feedbacks.feedback_suggest')
                ->whereNull('feedbacks.deleted_at')
                ->where('dictionaries.dct_type', '=', 'FBC-001')
                ->groupBy('category')
                ->orderBy('total', 'DESC')
                ->get();

            if (count($queries) > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'feedback suggestion', null),
                    'data' => $queries
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'feedback suggestion', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\FeedbackApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Feedback;

class Queries extends Controller
{
    //

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
                    'message' => 'Feedback Suggestion Found',
                    'data' => $queries
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Feedback Suggestion Not Found'
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

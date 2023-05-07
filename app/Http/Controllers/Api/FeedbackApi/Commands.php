<?php

namespace App\Http\Controllers\Api\FeedbackApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Generator;
use App\Models\Feedback;
use App\Helpers\Validation;

class Commands extends Controller
{
    //

    public function insertFeedback(Request $request) {

        try {

            $validator = Validation::getValidateFeedbackCreate($request);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $feedback = Feedback::create([
                    'id' => Generator::getUUID(),
                    'feedback_body' => $request->feedback_body,
                    'feedback_rate' => $request->feedback_rate,
                    'feedback_suggest' => $request->feedback_suggest,
                    'created_at' => date('Y-m-d H:i:s'),
                    'deleted_at' => null
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Feedback Created',
                    'data' => $feedback
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

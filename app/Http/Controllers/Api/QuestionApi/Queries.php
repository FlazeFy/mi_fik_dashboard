<?php

namespace App\Http\Controllers\Api\QuestionApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Question;

class Queries extends Controller
{
    //
    public function getQuestion($limit) {
        try {
            $question = Question::select('id', 'question_type', 'question_body', 'created_at', 'username')
                ->join('users', 'users.id', '=', 'questions.created_by')
                ->orderBy('created_at', 'DESC')
                ->paginate($limit);

            if ($question->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Question not found',
                    'data' => $question
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Question found',
                    'data' => $question
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAnswer($id) {
        try {
            $answer = Question::select('id', 'question_answer', 'updated_at', 'username')
                ->leftJoin('admins', 'admins.id', '=', 'questions.updated_by')
                ->where('questions.id', $id)
                ->first();

            if ($answer->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Answer not found',
                    'data' => $answer
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Answer found',
                    'data' => $answer
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

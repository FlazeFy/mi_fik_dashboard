<?php

namespace App\Http\Controllers\Api\QuestionApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Models\Question;

class Queries extends Controller
{
    //
    public function getQuestion($limit) {
        try {
            $question = Question::select('questions.id', 'question_type', 'question_body', 'question_answer', 'questions.created_at', 'questions.updated_at', 'username')
                ->join('users', 'users.id', '=', 'questions.created_by')
                ->orderBy('created_at', 'DESC')
                ->paginate($limit);

            if ($question->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Question not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
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

    public function getActiveQuestion($limit) {
        try {
            $question = Question::select('question_type', 'question_body', 'question_answer')
                ->where('is_active', '1')
                ->whereNotNull('question_answer')
                ->orderBy('created_at', 'DESC')
                ->paginate($limit);

            if ($question->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Question not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
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
            $answer = Question::select('questions.id', 'question_answer', 'questions.updated_at', 'username')
                ->leftJoin('admins', 'admins.id', '=', 'questions.updated_by')
                ->where('questions.id', $id)
                ->limit(1)
                ->get();

            if ($answer->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Answer not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
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

    public function getAnswerSuggestion($answer) {
        try {
            $que = Question::select('question_answer', 'username')
                ->join('admins', 'admins.id', '=', 'questions.updated_by')
                ->where('question_answer', 'LIKE', '%'.$answer.'%')
                ->whereNotNull('question_answer')
                ->limit(10)
                ->get();

            if ($que->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Answer not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Answer found',
                    'data' => $que
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMyQuestions(Request $request) {
        try {
            $user_id = $request->user()->id;

            $myQuestion = Question::getQuestionByUserId($user_id);

            if($myQuestion->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Question not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Question found',
                    'data' => $myQuestion
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

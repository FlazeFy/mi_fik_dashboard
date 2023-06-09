<?php

namespace App\Http\Controllers\Api\QuestionApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Question;

use App\Helpers\Generator;
use App\Helpers\Validation;

class Commands extends Controller
{
    //
    public function deleteQuestion(Request $request, $id){
        try{
            $user_id = $request->user()->id;

            $content = Question::where('id', $id)->update([
                'deleted_at' => date("Y-m-d H:i:s"),
                'deleted_by' => $user_id,
            ]);

            if($content != 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content deleted',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content not found',
                    'data' => null
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createQuestion(Request $request) {
        try {
            $user_id = $request->user()->id;
            $validator = Validation::getValidateQuestionFaq($request);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $content = Question::create([
                    'id' => Generator::getUUID(),
                    'question_type' => $request->question_type,
                    'question_body' => $request->question_body,
                    'question_answer' => null,
                    'is_active' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id,
                    'updated_at' => null,
                    'updated_by' => null,
                    'deleted_at' => null,
                    'deleted_by' => null,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Question created',
                    'data' => $content
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

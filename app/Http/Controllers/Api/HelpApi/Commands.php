<?php

namespace App\Http\Controllers\Api\HelpApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Validation;
use App\Helpers\Generator;
use App\Helpers\Converter;

use App\Models\Help;
use App\Models\History;

class Commands extends Controller
{
    public function addHelpType(Request $request){
        try{
            $user_id = $request->user()->id;
            $validator = Validation::getValidateHelp($request);
            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'message' => 'Add type failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "help",
                    'history_body' => "Has created a new type"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Add type failed',
                        'result' => $errors
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $type = Converter::getCleanQuotes(trim(strtolower($request->help_type)));
                    $checkType = Help::getAboutHelpType($type);

                    if($checkType == false){
                        $help = Help::create([
                            'id' => Generator::getUUID(),
                            'help_type' => $type,
                            'help_category' => null,
                            'help_body' => null,
                            'created_at' => date("Y-m-d H:i"),
                            'created_by' => $user_id,
                            'updated_at' => null,
                            'updated_by' => null,
                            'deleted_at' => null,
                            'deleted_by' => null,
                        ]);

                        History::create([
                            'id' => Generator::getUUID(),
                            'history_type' => $data->history_type, 
                            'context_id' => null, 
                            'history_body' => $data->history_body, 
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id
                        ]);
                        
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Success created new help category',
                            'data' => $help
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'result' => 'Failed to created new help category. The help type is already exist',
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }    
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

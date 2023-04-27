<?php

namespace App\Http\Controllers\Api\UserApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


use App\Models\User;
use App\Models\History;
use App\Models\UserRequest;

use App\Helpers\Validation;
use App\Helpers\Generator;
use App\Helpers\Converter;

class Commands extends Controller
{
    public function editUserData(Request $request)
    {
        try{
            $user_id = $request->user()->id;
            $validator = Validation::getValidateEditProfile($request, "user");

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "user",
                    'history_body' => "has updated the profile"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return response()->json([
                        'status' => 'failed',
                        'result' => $errors,
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $user = User::where('id', $user_id)->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'password' => $request->password,
                        'updated_at' => date("Y-m-d H:i"),
                        'updated_by' => $user_id,
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type,
                        'context_id' => null,
                        'history_body' => $data->history_body,
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'User profile updated',
                        'data' => $user." data updated"
                    ], Response::HTTP_OK);
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editUserImage(Request $request)
    {
        try{
            $user_id = $request->user()->id;
            $validator = Validation::getValidateEditProfileImage($request, "user");

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "user",
                    'history_body' => "has updated the profile"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return response()->json([
                        'status' => 'failed',
                        'result' => $errors,
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $user = User::where('id', $user_id)->update([
                        'image_url' => $request->image_url,
                        'updated_at' => date("Y-m-d H:i"),
                        'updated_by' => $user_id,
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type,
                        'context_id' => null,
                        'history_body' => $data->history_body,
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'User profile image updated',
                        'data' => $user." data updated"
                    ], Response::HTTP_OK);
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function request_role_api(Request $request) {
        try {
            $user_id = $request->user()->id;

            $hsAdd = new Request();
            $hsRemove = new Request();

            $objAdd = [
                'history_type' => "user",
                'history_body' => "request to add some role"
            ];
            $objRemove = [
                'history_type' => "user",
                'history_body' => "request to remove some role"
            ];

            $hsAdd->merge($objAdd);
            $hsRemove->merge($objRemove);

            $validatorHistoryAdd = Validation::getValidateHistory($hsAdd);
            $validatorHistoryRemove = Validation::getValidateHistory($hsRemove);

            if ($validatorHistoryAdd->fails() || $validatorHistoryRemove->fails()) {
                $errors = $validatorHistoryAdd->messages()." | ".$validatorHistoryRemove->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $add = [];
                $remove = [];

                $countAll = count($request->req_type);
                for($i = 0; $i < $countAll; $i++){
                    if($request->req_type[$i] == "add"){
                        array_push($add, $request->user_role[$i]);
                    } else if($request->req_type[$i] == "remove"){
                        array_push($remove, $request->user_role[$i]);
                    }
                }

                $roleAdd = Converter::getTag($add);
                $roleRemove = Converter::getTag($remove);

                $checkAdd = json_decode($roleAdd, true);
                $checkRemove = json_decode($roleRemove, true);

                if($checkAdd !== null || $checkRemove !== null || json_last_error() === JSON_ERROR_NONE){
                    if(count($add) > 0){
                        UserRequest::create([
                            'id' => Generator::getUUID(),
                            'tag_slug_name' => $checkAdd,
                            'request_type' => "add",
                            'created_at' => date("Y-m-d h:i:s"),
                            'created_by' => $user_id,
                            'updated_at' => null,
                            'updated_by' => null,
                            'is_rejected' => null,
                            'rejected_at' => null,
                            'rejected_by' => null,
                            'is_accepted' => 0,
                            'accepted_at' => null,
                            'accepted_by' => null,
                        ]);

                        History::create([
                            'id' => Generator::getUUID(),
                            'history_type' => $hsAdd->history_type,
                            'context_id' => null,
                            'history_body' => $hsAdd->history_body,
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d h:i:s"),
                            'created_by' => $user_id
                        ]);
                    }
                    if(count($remove) > 0){
                        UserRequest::create([
                            'id' => Generator::getUUID(),
                            'tag_slug_name' => $checkRemove,
                            'request_type' => "remove",
                            'created_at' => date("Y-m-d h:i:s"),
                            'created_by' => $user_id,
                            'updated_at' => null,
                            'updated_by' => null,
                            'is_rejected' => null,
                            'rejected_at' => null,
                            'rejected_by' => null,
                            'is_accepted' => 0,
                            'accepted_at' => null,
                            'accepted_by' => null,
                        ]);

                        History::create([
                            'id' => Generator::getUUID(),
                            'history_type' => $hsRemove->history_type,
                            'context_id' => null,
                            'history_body' => $hsRemove->history_body,
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d h:i:s"),
                            'created_by' => $user_id
                        ]);
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Request has been sended',
                        'data' => $add." | ".$remove
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Request failed',
                        'data' => $add." | ".$remove
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
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

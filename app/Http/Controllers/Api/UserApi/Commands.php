<?php

namespace App\Http\Controllers\Api\UserApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\History;
use App\Helpers\Validation;
use App\Helpers\Generator;

class Commands extends Controller
{
    public function editUserData(Request $request)
    {
        try{
            $user_id = $request->user()->id;
            $validator = Validation::getValidateEditProfile($request, "user");

            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                    'token' => null
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

                    return redirect()->back()->with('failed_message', $errors);
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
                    'token' => null
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

                    return redirect()->back()->with('failed_message', $errors);
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
}

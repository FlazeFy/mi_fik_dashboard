<?php

namespace App\Http\Controllers\Api\UserApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Admin;
use App\Models\PersonalAccessTokens;
use App\Models\Archive;
use App\Models\History;
use App\Models\UserRequest;
use App\Models\PasswordReset;

use App\Jobs\RecoverPassMailer;

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
                        'updated_at' => date("Y-m-d H:i"),
                        'updated_by' => $user_id,
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
        DB::beginTransaction();
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
                    $check = DB::table("personal_access_tokens")
                        ->where('tokenable_id', $user_id)
                        ->first();

                    if($check->tokenable_type === "App\\Models\\User"){ // User
                        DB::table("users")->where('id', $user_id)->update([
                            'image_url' => $request->image_url,
                            'updated_at' => date("Y-m-d H:i"),
                            'updated_by' => $user_id,
                        ]);
                    } else {
                        DB::table("admins")->where('id', $user_id)->update([
                            'image_url' => $request->image_url,
                            'updated_at' => date("Y-m-d H:i"),
                        ]);
                    }

                    DB::table("histories")->insert([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type,
                        'context_id' => null,
                        'history_body' => $data->history_body,
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);

                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'User profile image updated',
                    ], Response::HTTP_OK);
                }
            }
        } catch(\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function add_role(Request $request){
        DB::beginTransaction();

        try {
            $user_id = $request->user()->id;

            $roles = json_decode($request->user_role,true);
            $list_roles = "";
            foreach($roles as $rl){
                $list_roles .= $rl['tag_name'].",";
            }

            $hs = new Request();
            $obj = [
                'history_type' => "user",
                'history_body' => "add ".$list_roles." to ".$request->username." role"
            ];
            $hs->merge($obj);

            $validatorHistory = Validation::getValidateHistory($hs);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $oldR = DB::table("users")->select('id','role')
                    ->where('username',$request->username)
                    ->first(); 

                if($oldR->role == null){
                    $newR = $roles;
                } else {
                    $newR = array_merge(json_decode($oldR->role), $roles);
                }
                
                $newR = Converter::getTag($newR);
                $newR = json_decode($newR, true);

                DB::table("users")
                    ->where('id', $oldR->id)->update([
                        'role' => $newR,
                        'updated_at' => date("Y-m-d H:i"),
                        'updated_by' => $user_id,
                ]);

                DB::table("histories")->insert([
                    'id' => Generator::getUUID(),
                    'history_type' => $hs->history_type,
                    'context_id' => null,
                    'history_body' => $hs->history_body,
                    'history_send_to' => $oldR->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Role has been updated',
                    'data' => $newR
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'result' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function remove_role(Request $request){
        DB::beginTransaction();

        try {
            $user_id = $request->user()->id;

            $roles = json_decode($request->user_role,true);
            $list_roles = "";
            foreach($roles as $rl){
                $list_roles .= $rl['tag_name'].",";
            }

            $hs = new Request();
            $obj = [
                'history_type' => "user",
                'history_body' => "remove ".$list_roles." to ".$request->username." role"
            ];
            $hs->merge($obj);

            $validatorHistory = Validation::getValidateHistory($hs);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $oldR = DB::table("users")->select('id','role')
                    ->where('username',$request->username)
                    ->first(); 

                $uniqueKeys = [];
                $merge = array_merge($roles, json_decode($oldR->role, true));

                foreach ($merge as $mg) {
                    $key = $mg['slug_name'];
                    if (!in_array($key, $uniqueKeys)) {
                        $unique[] = $mg;
                        $uniqueKeys[] = $key;
                    } else {
                        $unique = array_filter($unique, 
                        function($val) use ($key) {
                            return $val['slug_name'] !== $key;
                        });
                    }
                }

                $roles = $unique;
                if(empty($roles)){
                    $roles = null;
                } else {
                    $roles = json_encode(array_values($roles));
                }

                DB::table("users")
                    ->where('id', $oldR->id)->update([
                        'role' => $roles,
                        'updated_at' => date("Y-m-d H:i"),
                        'updated_by' => $user_id,
                ]);

                DB::table("histories")->insert([
                    'id' => Generator::getUUID(),
                    'history_type' => $hs->history_type,
                    'context_id' => null,
                    'history_body' => $hs->history_body,
                    'history_send_to' => $oldR->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Role has been updated',
                    'data' => $roles
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'result' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function request_role_api(Request $request) {
        DB::beginTransaction();
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
                $checkAdd = null;
                $checkRemove = null;

                if(is_array($request->user_role)){
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
                } else {
                    if($request->req_type == "add"){
                        $checkAdd = $request->user_role;
                        $roleAdd = Converter::getTag(json_decode($checkAdd));
                        //$checkAdd = json_decode($roleAdd, true);
                        $checkAdd = $roleAdd;
                    } else if($request->req_type == "remove"){
                        $checkRemove = $request->user_role;
                        $roleRemove = Converter::getTag(json_decode($checkRemove));
                        //$checkRemove = json_decode($checkRemove, true);
                        $checkRemove = $roleRemove;
                    }
                }

                if($checkAdd !== null || $checkRemove !== null || json_last_error() === JSON_ERROR_NONE){
                    if(count($add) > 0 || (!is_array($request->user_role) && $request->req_type == "add")){
                        DB::table("users_requests")->insert([
                            'id' => Generator::getUUID(),
                            'tag_slug_name' => $checkAdd,
                            'request_type' => "add",
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id,
                            'is_rejected' => null,
                            'rejected_at' => null,
                            'rejected_by' => null,
                            'is_accepted' => 0,
                            'accepted_at' => null,
                            'accepted_by' => null,
                        ]);

                        DB::table("histories")->insert([
                            'id' => Generator::getUUID(),
                            'history_type' => $hsAdd->history_type,
                            'context_id' => null,
                            'history_body' => $hsAdd->history_body,
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id
                        ]);
                    }
                    if(count($remove) > 0 || (!is_array($request->user_role) && $request->req_type == "remove")){
                        DB::table("users_requests")->insert([
                            'id' => Generator::getUUID(),
                            'tag_slug_name' => $checkRemove,
                            'request_type' => "remove",
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id,
                            'is_rejected' => null,
                            'rejected_at' => null,
                            'rejected_by' => null,
                            'is_accepted' => 0,
                            'accepted_at' => null,
                            'accepted_by' => null,
                        ]);

                        DB::table("histories")->insert([
                            'id' => Generator::getUUID(),
                            'history_type' => $hsRemove->history_type,
                            'context_id' => null,
                            'history_body' => $hsRemove->history_body,
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id
                        ]);
                    }

                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Request has been sended',
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Request failed',
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function check_user(Request $request){
        try {
            $validator = Validation::getValidateUserStarter($request);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $found = User::where('username', $request->username)
                    ->orWhere('email', $request->email)
                    ->exists();

                if(!$found){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Validation success',
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'result' => 'Validation failed, username or email already registered',
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'result' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function request_recover_pass(Request $request){
        try {
            $validator = Validation::getValidateUserStarter($request);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $user = User::select("id")->where('username', $request->username)
                    ->where('email', $request->email)
                    ->first();

                if($user != null){
                    $active = PasswordReset::whereNull('validated_at')
                        ->where("created_by", $user->id)
                        ->first();

                    if($active == null){
                        $token = Generator::getTokenResetPass();

                        PasswordReset::create([
                            'id' => Generator::getUUID(),
                            'validation_token' => $token,
                            'new_password' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user->id,
                            'validated_at' => null
                        ]);

                        dispatch(new RecoverPassMailer($request->username, $request->email, $token));

                        return response()->json([
                            'status' => 'success',
                            'message' => 'We have send you password recovery token to your email. Please check it',
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'result' => 'Recovery failed. Your previous recover password has not finished yet',
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'result' => 'Validation failed, username or email does not exist',
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'result' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function validate_recover_pass(Request $request){
        DB::beginTransaction();
        try {
            $validator = Validation::getValidatePassRecover($request);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                if($request->type == "first"){
                    $reset = DB::table("passwords_resets")->select("passwords_resets.id", "passwords_resets.created_at")
                        ->join('users', 'users.id', '=', 'passwords_resets.created_by')
                        ->where('username', $request->username)
                        ->where('email', $request->email)
                        ->where('validation_token', $request->validation_token)
                        ->whereNull('validated_at')
                        ->first();

                    if($reset != null){
                        $remaining = Generator::getDateDiffSec($reset->created_at);
                        
                        if($remaining < 920){
                            DB::table("passwords_resets")->where("id", $reset->id)->update([
                                "validated_at" => date("Y-m-d H:i:s")
                            ]);

                            DB::commit();
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Token valid, now you can set up your new password',
                            ], Response::HTTP_OK);
                        } else {
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Times out',
                            ], Response::HTTP_UNPROCESSABLE_ENTITY);
                        }
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'result' => 'Token invalid, please try again',
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                } else {
                    DB::table("passwords_resets")->join('users', 'users.id', '=', 'passwords_resets.created_by')
                        ->where('username', $request->username)
                        ->where('email', $request->email)
                        ->whereNull('validated_at')
                        ->delete();

                    $token = Generator::getTokenResetPass();
                    $user = DB::table("users")->select("id")->where("username", $request->username)->first();

                    DB::table("passwords_resets")->insert([
                        'id' => Generator::getUUID(),
                        'validation_token' => $token,
                        'new_password' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user->id,
                        'validated_at' => null
                    ]);

                    dispatch(new RecoverPassMailer($request->username, $request->email, $token));

                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Token regenerated',
                    ], Response::HTTP_OK);
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'result' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function recover_pass(Request $request){
        DB::beginTransaction();
        try {
            $hs = new Request();
            $obj = [
                'history_type' => "user",
                'history_body' => "has changed password"
            ];
            $hs->merge($obj);

            $validatorHistory = Validation::getValidateHistory($hs);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $validator = Validation::getValidateNewPass($request);

                if ($validator->fails()) {
                    $errors = $validator->messages();

                    return response()->json([
                        'status' => 'failed',
                        'result' => $errors,
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $validPass = Validation::hasNumber($request->password);
                    if($validPass) {
                        $password = Hash::make($request->password);
                        DB::table("passwords_resets")->select("passwords_resets.id")
                            ->join('users', 'users.id', '=', 'passwords_resets.created_by')
                            ->where('username', $request->username)
                            ->where('validation_token', $request->validation_token)
                            ->whereNotNull('validated_at')
                            ->update([
                                "new_password" => $password
                            ]);
                        
                        DB::table("users")->where("username", $request->username)->update([
                            "password" => $password,
                            "updated_at" => date("Y-m-d H:i:s")
                        ]);

                        $user = DB::table("users")->select("id")->where("username", $request->username)->first();

                        DB::table("histories")->insert([
                            'id' => Generator::getUUID(),
                            'history_type' => $hs->history_type,
                            'context_id' => null,
                            'history_body' => $hs->history_body,
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user->id
                        ]);

                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Password has changed',
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'result' => [
                                "password" => [
                                    "Password must contain number"
                                ]
                            ],
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'result' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateFirebaseToken(Request $request, $token){
        try {
            $data = new Request();
            $obj = [
                'firebase_fcm_token' => $token,
            ];
            $data->merge($obj);
            $validator = Validation::getValidateFirebaseToken($data);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'error' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $user_id = $request->user()->id;
                
                User::where('id',$user_id)->update([
                    "firebase_fcm_token" => $token
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Token updated',
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request){
        DB::beginTransaction();
        try {
            $validator = Validation::getValidateUserRegister($request);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $validPass = Validation::hasNumber($request->password);
                if($validPass) {
                    $found = DB::table("users")->where('username', $request->username)
                        ->orWhere('email', $request->email)
                        ->exists();

                    if(!$found){
                        $uuid = Generator::getUUID();
                        $imageUrl = null;
                        if($request->has('image_url')){
                            $imageUrl = $request->image_url;
                        } 

                        $user = DB::table("users")->insert([
                            'id' => $uuid, 
                            'firebase_fcm_token' => null, 
                            'username' => $request->username, 
                            'email' => $request->email, 
                            'password' => Hash::make($request->password), 
                            'first_name' => $request->first_name, 
                            'last_name' => $request->last_name,  
                            'role' => null, 
                            'image_url' => $imageUrl, 
                            'batch_year' => $request->batch_year, 
                            'created_at' => date("Y-m-d H:i:s"), 
                            'updated_at' => null, 
                            'updated_by' => null, 
                            'deleted_at' => null,
                            'deleted_by' => null,
                            'accepted_at' => null,
                            'accepted_by' => null, 
                            'is_accepted' => 0
                        ]);

                        $data = new Request();
                        $obj = [
                            'history_type' => "user",
                            'history_body' => "has registered"
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
                            DB::table("histories")->insert([
                                'id' => Generator::getUUID(),
                                'history_type' => $data->history_type, 
                                'context_id' => null, 
                                'history_body' => $data->history_body, 
                                'history_send_to' => null,
                                'created_at' => date("Y-m-d H:i:s"),
                                'created_by' => $uuid,
                            ]);

                            $obj = [
                                'history_type' => "archive",
                                'history_body' => "has created a archive called 'My Archive'"
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
                                $archive_id = Generator::getUUID();
                                $archive = DB::table("archives")->insert([
                                    'id' => $archive_id,
                                    'slug_name' => "my-archive-".$uuid,
                                    'archive_name' => "My Archive",
                                    'archive_desc' => "This is default archive",
                                    'created_by' => $uuid,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_by' => null,
                                    'updated_at' => null
                                ]);

                                DB::table("histories")->insert([
                                    'id' => Generator::getUUID(),
                                    'history_type' => $data->history_type, 
                                    'context_id' => $archive_id, 
                                    'history_body' => $data->history_body, 
                                    'history_send_to' => null,
                                    'created_at' => date("Y-m-d H:i:s"),
                                    'created_by' => $uuid,
                                ]);

                                DB::commit();
                                return response()->json([
                                    'status' => 'success',
                                    'message' => 'User registration complete',
                                    'data' => $user
                                ], Response::HTTP_OK);
                            }
                        }
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'result' => 'Validation failed, username or email already registered',
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'result' => [
                            "password" => [
                                "Password must contain number"
                            ]
                        ],
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'result' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

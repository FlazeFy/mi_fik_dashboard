<?php

namespace App\Http\Controllers\Api\AuthApi;

use App\Models\User;
use App\Models\Admin;
use App\Helpers\Generator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Helpers\Validation;

class Commands extends Controller
{
    //
    public function login(Request $request, $env)
    {
        $validator = Validation::getValidateLogin($request);

        if ($validator->fails()) {
            $errors = $validator->messages();

            return response()->json([
                'status' => 'failed',
                'result' => $errors,
                'token' => null
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user = Admin::where('username', $request->username)->first();
            $role = 1;
            if(($user == null && $env == "mobile") || ($user == null && $env == "web") || ($user != null && $env == "web") || ($user == null && $env == "mobile")){
                if($user == null){
                    $user = User::where('username', $request->username)->first();
                    $role = 0;
                }

                $allow = true;
                if($role == 0 && $user){
                    if($user->role != null){
                        foreach($user->role as $rl){
                            if($rl['slug_name'] == "student" && $env == "web"){
                                $allow = false;
                                break;
                            }
                        }
                    } else {
                        $allow = true;
                    }
                } else if($role == 1 && $user){
                    $allow = true;
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'result' => Generator::getMessageTemplate("business_read_failed", 'user', null),
                        'token' => null,                
                    ], Response::HTTP_UNAUTHORIZED);
                }

                if($allow){
                    if (!$user || !Hash::check($request->password, $user->password)) {
                        //if (!$user || ($request->password != $user->password)) {
                        return response()->json([
                            'status' => 'failed',
                            'result' => Generator::getMessageTemplate("custom", 'wrong username or password', null),
                            'token' => null,                
                        ], Response::HTTP_UNAUTHORIZED);
                    } else {
                        $token = $user->createToken('login')->plainTextToken;
        
                        return response()->json([
                            'status' => 'success',
                            'result' => $user,
                            'token' => $token,  
                            'role' => $role                  
                        ], Response::HTTP_OK);
                    }
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'result' => Generator::getMessageTemplate("custom", "Sorry, student doesn't have access to MI-FIK web", null),
                        'token' => null,                
                    ], Response::HTTP_UNAUTHORIZED);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'result' => Generator::getMessageTemplate("custom", "Sorry, admin doesn't have access to MI-FIK mobile", null),
                    'token' => null,                
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        
    }
}

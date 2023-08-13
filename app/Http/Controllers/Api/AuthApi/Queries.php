<?php

namespace App\Http\Controllers\Api\AuthApi;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Helpers\Generator;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class Queries extends Controller
{
    public function logout(Request $request, $env)
    {
        $user_id = $request->user()->id;
        $check = Admin::where('id', $user_id)->first();

        if($check == null){
            // Student or Lecturer
            if($env == "mobile"){
                $user = User::where('id', $user_id)->update([
                    'firebase_fcm_token' => null
                ]);
                $request->user()->currentAccessToken()->delete();
                return response()->json([
                    'message' => Generator::getMessageTemplate("custom", 'logout success', null)
                ], Response::HTTP_OK);
            } else if($env == "web"){
                $user = User::select('role')->where('id', $user_id)->first();
                $allow = true;

                if($user){
                    foreach($user->role as $rl){
                        if($rl['slug_name'] == "student" && $env == "web"){
                            $allow = false;
                            break;
                        }
                    }
                }

                if($allow){
                    $request->user()->currentAccessToken()->delete();
                    return response()->json([
                        'message' => Generator::getMessageTemplate("custom", 'logout success', null)
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'result' => Generator::getMessageTemplate("custom", "Sorry, student doesn't have access to MI-FIK web", null),
                        'token' => null,                
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }
        } else {
            // Admin
            if($env == "web"){
                $request->user()->currentAccessToken()->delete();
                return response()->json([
                    'message' => Generator::getMessageTemplate("custom", 'logout success', null)
                ], Response::HTTP_OK);
            } else if($env == "mobile"){
                return response()->json([
                    'status' => 'failed',
                    'result' => Generator::getMessageTemplate("custom", "Sorry, admin doesn't have access to MI-FIK mobile", null),
                    'token' => null,                
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
    }
}

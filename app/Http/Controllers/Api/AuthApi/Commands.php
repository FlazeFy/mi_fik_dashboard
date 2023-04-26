<?php

namespace App\Http\Controllers\Api\AuthApi;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Helpers\Validation;

class Commands extends Controller
{
    //
    public function login(Request $request)
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
            if($user == null){
                $user = User::where('username', $request->username)->first();
                $role = 0;
            }

            if (!$user || ($request->password != $user->password)) {
                return response()->json([
                    'status' => 'failed',
                    'result' => 'Wrong password',
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
        }
        
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Helpers\Validation;

class AuthApi extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validation::getValidateLogin($request);

        if ($validator->fails()) {
            $errors = $validator->messages();

            return response()->json([
                'status' => 422,
                'result' => $errors,
                'token' => null
            ], Response::HTTP_OK);
        } else {
            $user = Admin::where('username', $request->username)->first();

            if (!$user || ($request->password != $user->password)) {
                throw ValidationException::withMessages([
                    'result' => ['The provided credentials are incorrect.'],
                ]);
            } else {
                $token = $user->createToken('login')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'result' => $user,
                    'token' => $token,                    
                ], Response::HTTP_OK);
            }
        }
        
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout success'
        ], Response::HTTP_OK);
    }
}

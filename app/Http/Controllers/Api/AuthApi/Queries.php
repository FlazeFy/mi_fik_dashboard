<?php

namespace App\Http\Controllers\Api\AuthApi;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Helpers\Generator;
use Illuminate\Support\Facades\Hash;

class Queries extends Controller
{
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => Generator::getMessageTemplate("custom", 'logout success', null)
        ], Response::HTTP_OK);
    }
}

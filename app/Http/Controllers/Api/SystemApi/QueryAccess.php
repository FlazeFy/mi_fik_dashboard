<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Query;

use App\Models\PersonalAccessTokens;

class QueryAccess extends Controller
{
    //
    public function getAllPersonalAccessToken(Request $request, $limit) {
        try {
            $user_id = $request->user()->id;
            $select = Query::getSelectTemplate("access_info");

            $res = PersonalAccessTokens::selectRaw($select)
                ->leftJoin('users', 'users.id', '=', 'personal_access_tokens.tokenable_id')
                ->leftJoin('admins', 'admins.id', '=', 'personal_access_tokens.tokenable_id')
                ->orderby('last_used_at','DESC')
                ->paginate($limit);

            if ($res->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user', null),
                    'data' => $res
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

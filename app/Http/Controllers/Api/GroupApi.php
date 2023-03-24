<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\UserGroup;
use App\Helpers\Query;

class GroupApi extends Controller
{
    public function getAllGroup($limit)
    {
        try{
            $select = Query::getSelectTemplate("group_detail");

            $user = UserGroup::selectRaw($select)
                ->orderBy('created_at', 'ASC')
                ->paginate($limit);

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Group Not Found',
                    'data' => $user
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Group Found',
                    'data' => $user
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

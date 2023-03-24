<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\UserGroup;
use App\Helpers\Query;

class GroupApi extends Controller
{
    public function getAllGroup($limit, $order)
    {
        try{
            $select = Query::getSelectTemplate("group_detail");
            $ord = explode("__", $order);

            $user = UserGroup::selectRaw($select)
                ->join('groups_relations', 'groups_relations.group_id', '=', 'users_groups.id')
                ->groupBy('id')
                ->orderBy($ord[0], $ord[1])
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

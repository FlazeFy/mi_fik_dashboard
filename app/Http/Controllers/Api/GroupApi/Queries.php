<?php

namespace App\Http\Controllers\Api\GroupApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\UserGroup;
use App\Helpers\Query;

class Queries extends Controller
{
    public function getAllGroup($limit, $order, $find)
    {
        try{
            $select = Query::getSelectTemplate("group_detail");
            $ord = explode("__", $order);
            $find = trim($find);

            if($find != null){
                $group = UserGroup::selectRaw($select)
                    ->leftjoin('groups_relations', 'groups_relations.group_id', '=', 'users_groups.id')
                    ->groupBy('users_groups.id')
                    ->orderBy($ord[0], $ord[1])
                    ->whereNull('deleted_at')
                    ->whereRaw("group_name LIKE '%".$find."%'")
                    ->paginate($limit);
            } else {
                $group = UserGroup::selectRaw($select)
                    ->leftjoin('groups_relations', 'groups_relations.group_id', '=', 'users_groups.id')
                    ->groupBy('users_groups.id')
                    ->orderBy($ord[0], $ord[1])
                    ->whereNull('deleted_at')
                    ->paginate($limit);
            }

            if ($group->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Group Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Group Found',
                    'data' => $group
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

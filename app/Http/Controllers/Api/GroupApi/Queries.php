<?php

namespace App\Http\Controllers\Api\GroupApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\UserGroup;
use App\Models\User;
use App\Models\GroupRelation;
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

    public function getGroupRelationBySlug($slug, $limit)
    {
        try{
            $select = Query::getSelectTemplate("group_relation");
            
            $group = GroupRelation::selectRaw($select)
                ->join('users', 'users.id', '=', 'groups_relations.user_id')
                ->join('users_groups', 'users_groups.id', '=', 'groups_relations.group_id')
                ->where('users_groups.slug_name', $slug)
                ->paginate($limit);

            if ($group->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Group Relation Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Group Relation Found',
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

    public function getAvailableUserBySlug($slug, $filter_name, $limit, $order){
        try{
            $select = Query::getSelectTemplate("user_detail");
            $ord = explode("__", $order);
            
            if(strpos($filter_name, "_")){
                $name = explode("_", $filter_name);

                if($name[0] == "all" && $name[1] == "all"){
                    $group = User::selectRaw($select)->whereNotIn('id', function($query) use ($slug) {
                        $query->select('user_id')
                        ->from('groups_relations')
                        ->join('users_groups', 'users_groups.id', '=', 'groups_relations.group_id')
                        ->where('users_groups.slug_name', $slug);
                    })->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
                } else if($name[0] != "all" && $name[1] == "all"){
                    $group = User::selectRaw($select)->whereNotIn('id', function($query) use ($slug) {
                        $query->select('user_id')
                        ->from('groups_relations')
                        ->join('users_groups', 'users_groups.id', '=', 'groups_relations.group_id')
                        ->where('users_groups.slug_name', $slug);
                    })->whereRaw("first_name LIKE '".$name[0]."%'")
                    ->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
                } else if($name[0] == "all" && $name[1] != "all"){
                    $group = User::selectRaw($select)->whereNotIn('id', function($query) use ($slug) {
                        $query->select('user_id')
                        ->from('groups_relations')
                        ->join('users_groups', 'users_groups.id', '=', 'groups_relations.group_id')
                        ->where('users_groups.slug_name', $slug);
                    })->whereRaw("last_name LIKE '".$name[1]."%'")
                    ->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
                } else {
                    $group = User::selectRaw($select)->whereNotIn('id', function($query) use ($slug) {
                        $query->select('user_id')
                        ->from('groups_relations')
                        ->join('users_groups', 'users_groups.id', '=', 'groups_relations.group_id')
                        ->where('users_groups.slug_name', $slug);
                    })->whereRaw("first_name LIKE '".$name[0]."%'")
                    ->whereRaw("last_name LIKE '".$name[1]."%'")
                    ->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
                }
            } else {
                $group = User::selectRaw($select)->whereNotIn('id', function($query) use ($slug) {
                    $query->select('user_id')
                    ->from('groups_relations')
                    ->join('users_groups', 'users_groups.id', '=', 'groups_relations.group_id')
                    ->where('users_groups.slug_name', $slug);
                })->whereRaw("CONCAT(first_name, ' ', COALESCE(last_name, '')) LIKE '%".trim($filter_name)."%'")
                ->orderBy($ord[0], $ord[1])
                ->paginate($limit);
            }

            if ($group->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Group Relation Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Group Relation Found',
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

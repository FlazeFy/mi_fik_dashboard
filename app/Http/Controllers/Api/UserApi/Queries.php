<?php

namespace App\Http\Controllers\Api\UserApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\UserRequest;
use App\Helpers\Query;

class Queries extends Controller
{
    public function getNewUserRequest()
    {
        try{
            $select = Query::getSelectTemplate("user_request_new");

            $user = User::selectRaw($select)
                ->where('role', null)
                ->orWhere('is_accepted', 0)
                ->orderBy('created_at', 'DESC')
                ->paginate(12);

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User request Not Found',
                    'data' => $user
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User request Found',
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

    public function getUser($filter_name, $limit, $order)
    {
        try{
            $select = Query::getSelectTemplate("user_detail");

            $name = explode("_", $filter_name);
            $ord = explode("__", $order);

            if($name[0] == "all" && $name[1] == "all"){
                $user = User::selectRaw($select)
                    ->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
            } else if($name[0] != "all" && $name[1] == "all"){
                $user = User::selectRaw($select)
                    ->whereRaw("first_name LIKE '".$name[0]."%'")
                    ->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
            } else if($name[0] == "all" && $name[1] != "all"){
                $user = User::selectRaw($select)
                    ->whereRaw("last_name LIKE '".$name[1]."%'")
                    ->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
            } else {
                $user = User::selectRaw($select)
                    ->whereRaw("first_name LIKE '".$name[0]."%'")
                    ->whereRaw("last_name LIKE '".$name[1]."%'")
                    ->orderBy($ord[0], $ord[1])
                    ->paginate($limit);
            }

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Not Found',
                    'data' => $user
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User '.count($user).' Found',
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

    public function getOldUserRequest()
    {
        try{
            $select = Query::getSelectTemplate("user_request_old");

            $user = UserRequest::selectRaw($select)
                ->join('users', 'users.id', '=', 'users_requests.created_by')
                ->where('is_rejected', null)
                ->where('users_requests.is_accepted', 0)
                ->orderBy('created_at', 'ASC')
                ->paginate(12);

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User request Not Found',
                    'data' => $user
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User request Found',
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

    public function getUserRejectedRequest()
    {
        try{
            $select = Query::getSelectTemplate("user_request_old");

            $user = UserRequest::selectRaw($select)
                ->join('users', 'users.id', '=', 'users_requests.created_by')
                ->where('is_rejected', 1)
                ->where('users_requests.is_accepted', 0)
                ->orderBy('created_at', 'DESC')
                ->paginate(12);

            //Must be union with new user

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User request Not Found',
                    'data' => $user
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User request Found',
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

    public function getUserDetail($slug_name){
        try{
            $select = Query::getSelectTemplate("user_detail");

            $user = User::selectRaw($select)
                ->where('slug_name', $slug_name)
                ->limit(1)
                ->get();

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Not Found',
                    'data' => $user
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Found',
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

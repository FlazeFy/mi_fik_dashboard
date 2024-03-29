<?php

namespace App\Http\Controllers\Api\UserApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\UserRequest;
use App\Helpers\Query;
use App\Helpers\Generator;

class Queries extends Controller
{
    public function getNewUserRequest($fullname)
    {
        try{
            $select = Query::getSelectTemplate("user_request_new");

            $user = User::selectRaw($select)
                ->where('role', null)
                ->whereRaw("CONCAT(first_name,' ',COALESCE(last_name, '')) LIKE '%".$fullname."%'")
                ->orWhere('is_accepted', 0)
                ->whereRaw("CONCAT(first_name,' ',COALESCE(last_name, '')) LIKE '%".$fullname."%'")
                ->orderBy('created_at', 'DESC')
                ->paginate(12);

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user request', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user request', null),
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser($filter_name, $limit, $order, $slug)
    {
        try{
            $select = Query::getSelectTemplate("user_detail");
            $query = null;

            if($slug != "all"){
                $i = 1;
                $query = "";
                $filter_tag = explode(",", $slug);

                foreach($filter_tag as $ft){
                    $stmt = 'role like '."'".'%"slug_name":"'.$ft.'"%'."'";

                    if($i != 1){
                        $query = substr_replace($query, " ".$stmt." OR", 0, 0);
                    } else {
                        $query = substr_replace($query, " ".$stmt, 0, 0);
                    }
                    $i++;
                }
            }

            if(strpos($filter_name, "_")){
                $name = explode("_", $filter_name);
                $ord = explode("__", $order);

                if($name[0] == "all" && $name[1] == "all"){
                    $user = User::selectRaw($select)
                        ->orderBy($ord[0], $ord[1]);
                } else if($name[0] != "all" && $name[1] == "all"){
                    $user = User::selectRaw($select)
                        ->whereRaw("first_name LIKE '".$name[0]."%'")
                        ->orderBy($ord[0], $ord[1]);
                } else if($name[0] == "all" && $name[1] != "all"){
                    $user = User::selectRaw($select)
                        ->whereRaw("last_name LIKE '".$name[1]."%'")
                        ->orderBy($ord[0], $ord[1]);
                } else {
                    $user = User::selectRaw($select)
                        ->whereRaw("first_name LIKE '".$name[0]."%'")
                        ->whereRaw("last_name LIKE '".$name[1]."%'")
                        ->orderBy($ord[0], $ord[1]);
                }
            } else {
                $user = User::selectRaw($select)
                    ->whereRaw("CONCAT(first_name, ' ', COALESCE(last_name, '')) LIKE '%".trim($filter_name)."%'");
            }

            if($query !== null){
                $user->whereRaw($query);
            }

            $user = $user->paginate($limit);

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'User '.count($user).' Found', null),
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOldUserRequest($fullname)
    {
        try{
            $select = Query::getSelectTemplate("user_request_old");

            $user = UserRequest::selectRaw($select)
                ->join('users', 'users.id', '=', 'users_requests.created_by')
                ->where('is_rejected', null)
                ->whereRaw("CONCAT(first_name,' ',COALESCE(last_name, '')) LIKE '%".$fullname."%'")
                ->where('users_requests.is_accepted', 0)
                ->where('users.is_accepted', 1)
                ->orderBy('created_at', 'ASC')
                ->paginate(12);

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user request', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user request', null),
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
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
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user request', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user request', null),
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserDetail($username){
        try{
            $select = Query::getSelectTemplate("user_detail");

            $user = User::selectRaw($select)
                ->where('username', $username)
                ->limit(1)
                ->get();

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user', null),
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMyProfile(Request $request){
        try{
            $user_id = $request->user()->id;

            $user = User::select('username', 'email', 'password', 'first_name', 'last_name', 'role', 'image_url', 'batch_year', 'created_at', 'updated_at')
                ->where('id', $user_id)
                ->limit(1)
                ->get();

            if ($user->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user', null),
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMyRole($username){
        try{
            $user = User::select('role')
                ->where('username', $username)
                ->first();

            if ($user == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user', null),
                    'data' => $user['role']
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMyRequest(Request $request){
        try{
            $user_id = $request->user()->id;
            $select = Query::getSelectTemplate("user_request_old");

            $user = UserRequest::getRecentlyRequest($user_id);

            if ($user == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'user request', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'user request', null),
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

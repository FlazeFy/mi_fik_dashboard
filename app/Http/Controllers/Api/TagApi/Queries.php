<?php

namespace App\Http\Controllers\Api\TagApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Generator;

use App\Models\Tag;
use App\Models\ContentDetail;
use App\Models\User;

class Queries extends Controller
{
    public function getAllTag($find, $limit){
        try{
            $tag = Tag::select('tags.slug_name', 'tag_name', 'dictionaries.dct_name as tag_category')
                ->leftjoin('dictionaries','dictionaries.slug_name','=','tags.tag_category')
                ->whereNull('tags.deleted_at')
                ->orderBy('tag_name', 'ASC')
                ->orderBy('tags.created_at', 'DESC')
                ->orderBy('tags.id', 'DESC');
            if($find != "%20" && trim($find) != ""){
                $tag->whereRaw("tag_name LIKE '%".$find."%'");
            } 
            
            $tag = $tag->paginate($limit);

            if ($tag->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'tag', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read", 'tag', null),
                    'data' => $tag
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTotalTagUsed($slug){
        try{
            $content = ContentDetail::selectRaw('1')
                ->whereRaw('content_tag like '."'".'%"slug_name":"'.$slug.'"%'."'")
                ->get();

            $user = User::selectRaw('1')
                ->whereRaw('role like '."'".'%"slug_name":"'.$slug.'"%'."'")
                ->get();

            $content = count($content);
            $user = count($user);

            if ($content == 0 && $user == 0) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("custom", 'tag has not used in any user or content', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                $obj = [
                    "total_content" => $content,
                    "total_user" => $user,
                    "total" => $content + $user
                ];

                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'tag', null),
                    'data' => $obj
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllTagByCat($cat, $limit){
        try{
            if($cat != "all" && $cat != "All"){
                $tag = Tag::select('tags.slug_name', 'tag_name', 'tag_desc', 'dictionaries.dct_name as tag_category')
                    ->leftjoin('dictionaries','dictionaries.slug_name','=','tags.tag_category')
                    ->orderBy('tag_name', 'ASC')
                    ->orderBy('tags.created_at', 'DESC')
                    ->orderBy('tags.id', 'DESC')
                    ->where('tags.tag_category', $cat)
                    ->whereNull('tags.deleted_at')
                    ->paginate($limit);
            } else {
                $tag = Tag::select('tags.slug_name', 'tag_name', 'tag_desc', 'dictionaries.dct_name as tag_category')
                    ->leftjoin('dictionaries','dictionaries.slug_name','=','tags.tag_category')
                    ->orderBy('tag_name', 'DESC')
                    ->orderBy('tags.created_at', 'DESC')
                    ->orderBy('tags.id', 'DESC')
                    ->whereNull('tags.deleted_at')
                    ->paginate($limit);
            }

            if ($tag->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'tag', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'tag', null),
                    'data' => $tag
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

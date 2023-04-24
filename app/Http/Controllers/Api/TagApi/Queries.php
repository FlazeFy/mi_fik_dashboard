<?php

namespace App\Http\Controllers\Api\TagApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Tag;

class Queries extends Controller
{
    public function getAllTag($limit){
        try{
            $tag = Tag::select('slug_name', 'tag_name')
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->paginate($limit);

            if ($tag->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tag Not Found',
                    'data' => $tag
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tag Found',
                    'data' => $tag
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

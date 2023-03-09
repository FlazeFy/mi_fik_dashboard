<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Tag;

class TagApi extends Controller
{
    public function getAllTag(){
        $tag = Tag::select('slug_name', 'tag_name')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        if ($tag->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tag Not Found',
                'data' => $tag
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Tag Found',
                'data' => $tag
            ], Response::HTTP_OK);
        }
    }
}

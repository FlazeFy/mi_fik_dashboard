<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Generator;

use App\Models\Tag;

class TagApi extends Controller
{
    public function getAllTag(){
        try{
            $tag = Tag::select('slug_name', 'tag_name')
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->paginate(15);

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
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addTag(Request $request){
        try{
            //Validate name avaiability
            $check = Tag::where('tag_name', $request->tag_name)->get();

            if(count($check) == 0 && strtolower(str_replace(" ","", $request->tag_name)) != "all"){
                $slug = Generator::getSlugName($request->tag_name, "tag");

                $tag = Tag::create([
                    'slug_name' => $slug,
                    'tag_name' => $request->tag_name,
                    'tag_desc' => $request->tag_desc,
                    'created_at' => date("Y-m-d h:i:s"),
                    'updated_at' => null,
                    'deleted_at' => null,
                    'created_by' => $request->user_id,
                    'updated_by' => null,
                    'deleted_by' => null
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Tag created',
                    'data' => $tag
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Create tag failed, use unique name',
                    'data' => null
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateTag(Request $request, $id){
        try{
            //Validate name avaiability
            $check = Tag::where('tag_name', $request->tag_name)->get();

            if((count($check) == 0 || $request->update_type == "desc") && strtolower(str_replace(" ","", $request->tag_name)) != "all"){
                $slug = Generator::getSlugName($request->tag_name, "tag");

                $tag = Tag::where('id', $id)->update([
                    'slug_name' => $slug,
                    'tag_name' => $request->tag_name,
                    'tag_desc' => $request->tag_desc,
                    'updated_at' => date("Y-m-d h:i:s"),
                    'updated_by' => $request->user_id,
                ]);

                if($tag != 0){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Tag updated',
                        'data' => $tag
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Tag not found',
                        'data' => null
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Updated failed, use unique name',
                    'data' => null
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteTag(Request $request, $id){
        try{
            $tag = Tag::where('id', $id)->update([
                'deleted_at' => date("Y-m-d h:i:s"),
                'deleted_by' => $request->user_id,
            ]);

            if($tag != 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tag deleted',
                    'data' => $tag
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Tag not found',
                    'data' => null
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroyTag($id){
        try{
            $tag = Tag::destroy($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Tag permanentaly deleted',
                'data' => $tag
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

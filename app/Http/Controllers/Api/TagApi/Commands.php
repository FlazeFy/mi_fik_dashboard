<?php

namespace App\Http\Controllers\TagApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Tag;

class Commands extends Controller
{
    public function addTag(Request $request){
        try{
            //Validate name avaiability
            $check = Tag::where('tag_name', $request->tag_name)->get();

            //Helpers
            $validator = Validation::getValidateTag($request);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 422,
                    'message' => 'Add tag failed',
                    'error' => $errors
                ], Response::HTTP_BAD_REQUEST);
            } else {
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

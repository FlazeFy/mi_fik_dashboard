<?php

namespace App\Http\Controllers\Api\ContentApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Converter;
use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Query;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\ContentViewer;
use App\Models\Task;

class CommandContent extends Controller
{
    public function deleteContent(Request $request, $id){
        try{
            $user_id = $request->user()->id;

            $content = ContentHeader::where('id', $id)->update([
                'deleted_at' => date("Y-m-d H:i:s"),
                'deleted_by' => $user_id,
            ]);

            if($content != 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content deleted',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content not found',
                    'data' => null
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroyContent($id){
        try{
            $content = ContentHeader::destroy($id);

            ContentDetail::where('content_id', $id)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Content permanentaly deleted',
                'data' => $content
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addContent(Request $request){
        try{
            //Inital variable
            $draft = 0;
            $failed_attach = false;
            $user_id = $request->user()->id;

            //Helpers
            $validator = Validation::getValidateEvent($request);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'message' => 'Add content failed',
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $tag = null;

                if(is_array($request->content_tag) && $request->content_tag != null){
                    $tag = Converter::getTag($request->content_tag);
                    $tag = json_decode($tag, true);
                } else if($request->content_tag != null){
                    // $tag = Converter::getTag(json_decode($request->content_tag));
                    // $tag = json_decode($tag, true);
                    $tag = $request->content_tag;
                }

                $fulldate_start = Converter::getFullDate($request->content_date_start, $request->content_time_start);
                $fulldate_end = Converter::getFullDate($request->content_date_end, $request->content_time_end);
                $slug = Generator::getSlugName($request->content_title, "content");
                $uuid = Generator::getUUID();

                if($request->content_image || $request->content_image != ""){
                    $imageURL = $request->content_image;
                } else {
                    $imageURL = null;
                }

                $header = ContentHeader::create([
                    'id' => $uuid,
                    'slug_name' => $slug,
                    'content_title' => $request->content_title,
                    'content_desc' => $request->content_desc,
                    'content_date_start' => $fulldate_start,
                    'content_date_end' => $fulldate_end,
                    'content_reminder' => $request->content_reminder,
                    'content_image' => $imageURL,
                    'is_draft' => $draft,
                    'created_at' => date("Y-m-d H:i"),
                    'created_by' => $user_id, //for now
                    'updated_at' => null,
                    'updated_by' => null,
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);

                if($tag != null || $request->has('content_attach')){
                    $detail = ContentDetail::create([
                        'id' => Generator::getUUID(),
                        'content_id' => $uuid,
                        'content_attach' => $request->content_attach,
                        'content_tag' => $tag,
                        'content_loc' => $request->content_loc,
                        'created_at' => date("Y-m-d H:i"),
                        'updated_at' => null
                    ]);
                }

                $full_result = ([
                    "header" => $header,
                    "detail" => $detail
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Content created',
                    'data' => $full_result
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addView($slug_name, Request $request){
        try{
            $content_id = Generator::getContentId($slug_name); //Fix this
            // $user_id = Generator::getUserId($user_slug, $user_role);
            $user_id = $request->user()->id;
            $viewer = ContentViewer::getViewByContentIdUserId($content_id, $user_id);

            if($content_id != null && $user_id != null){
                if($viewer){
                    $res = ContentViewer::where('id', $viewer)->update([
                        'created_at' => date("Y-m-d H:i:s")
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Content views updated',
                    ], Response::HTTP_OK);
                } else {
                    $res = ContentViewer::create([
                        'id' => Generator::getUUID(),
                        'content_id' => $content_id,
                        'type_viewer' => 0,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Content views created',
                        'data' => $res
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User or content not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStatsMostViewedEvent(){
        try{
            $res= ContentDetail::getMostViewedEvent(7);


            if(count($res) > 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Statistic found',
                    'data' => $res
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Statistic not found',
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
}

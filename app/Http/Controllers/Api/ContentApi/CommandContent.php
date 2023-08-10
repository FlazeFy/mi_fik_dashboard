<?php

namespace App\Http\Controllers\Api\ContentApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Helpers\Converter;
use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Query;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\ContentViewer;
use App\Models\Task;
use App\Models\History;

use App\Jobs\ProcessMailer;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

class CommandContent extends Controller
{
    public function addContent(Request $request){
        $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
        $messaging = $factory->createMessaging();

        DB::beginTransaction();

        try{
            $user_id = $request->user()->id;
            $grole = Query::getAccessRole($user_id, true);

            if($grole != "student" || $grole == null){
                $data = new Request();
                $obj = [
                    'history_type' => "event",
                    'history_body' => "has created an event"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return response()->json([
                        'status' => 'failed',
                        'message' => Generator::getMessageTemplate("business_create_failed", 'event', null),
                        'result' => $errors
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    //Inital variable
                    $draft = 0;

                    //Helpers
                    $validator = Validation::getValidateEvent($request);

                    if ($validator->fails()) {
                        $errors = $validator->messages();

                        return response()->json([
                            'status' => 'failed',
                            'message' => Generator::getMessageTemplate("business_create_failed", 'event', null),
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

                        $header = [
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
                        ];

                        DB::table("contents_headers")->insert($header);

                        if($tag != null || $request->has('content_attach')){
                            $detail = [
                                'id' => Generator::getUUID(),
                                'content_id' => $uuid,
                                'content_attach' => $request->content_attach,
                                'content_tag' => $tag,
                                'content_loc' => $request->content_loc,
                                'created_at' => date("Y-m-d H:i"),
                                'updated_at' => null
                            ];

                            DB::table("contents_details")->insert($detail);
                        }

                        $full_result = ([
                            "header" => $header,
                            "detail" => $detail
                        ]);

                        DB::table("histories")->insert([
                            'id' => Generator::getUUID(),
                            'history_type' => $data->history_type,
                            'context_id' => $uuid,
                            'history_body' => $data->history_body,
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id
                        ]);

                        $users = DB::table("users")->select("username", "firebase_fcm_token","email")
                            ->where("id",$user_id)
                            ->first();

                        if($users){
                            $notif_body = "You has been created an event called '".$request->content_title."'";
                            $firebase_token = $users->firebase_fcm_token;
                            if($firebase_token){
                                $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                                if($validateRegister['valid'] != null){
                                    $notif_title = "Hello ".$users->username.", you got an information";
                                    $message = CloudMessage::withTarget('token', $firebase_token)
                                        ->withNotification(
                                            FireNotif::create($notif_body)
                                            ->withTitle($notif_title)
                                            ->withBody(strtoupper($data->history_type)." ".$notif_body)
                                        )
                                        ->withData([
                                            'slug' => $slug,
                                            'module' => 'event'
                                        ]);
                                    $response = $messaging->send($message);
                                } else {
                                    DB::table("users")->where('id', $user_id)->update([
                                        "firebase_fcm_token" => null
                                    ]);
                                }
                            }
                        }
            
                        DB::commit();

                        if($users->email){
                            //Mail::to($users->email)->send(new OrganizerEmail($header, $detail));
                            dispatch(new ProcessMailer($header, $detail, $users->email));
                        }

                        return response()->json([
                            'status' => 'success',
                            'message' => Generator::getMessageTemplate("business_create", 'event', null),
                            'data' => $full_result
                        ], Response::HTTP_OK);
                    }
                }
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_create_failed", "event. You don't have access to use this feature", null),
                    'data' => null
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addView($slug_name, Request $request){
        try{
            $content_id = Generator::getContentId($slug_name);
            $user_id = $request->user()->id;
            $viewer = ContentViewer::getViewByContentIdUserId($content_id, $user_id);

            if($content_id != null && $user_id != null){
                if($viewer){
                    $res = ContentViewer::where('id', $viewer)->update([
                        'created_at' => date("Y-m-d H:i:s")
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => Generator::getMessageTemplate("business_update", 'content view', null),
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
                        'message' => Generator::getMessageTemplate("business_create", 'content view', null),
                        'data' => $res
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'content or user', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editContentImage(Request $request,$slug){
        try{
            $data = new Request();
            $obj = [
                'history_type' => "event",
                'history_body' => "has updated an event image header"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_update_failed", 'event image', null),
                    'result' => $errors
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $user_id = $request->user()->id;

                $content = ContentHeader::select("id")
                    ->where("slug_name",$slug)
                    ->first();

                $rows = ContentHeader::where("id",$content->id)->where('created_by', $user_id)
                    ->update([
                        'content_image'=> $request->content_image,
                        'updated_at' => date("Y-m-d H:i:s"),
                        'updated_by' => $user_id,
                ]);

                if($rows > 0){
                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type,
                        'context_id' => $content->id,
                        'history_body' => $data->history_body,
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => Generator::getMessageTemplate("business_update", 'event image', null),
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => Generator::getMessageTemplate("failed_owner_exist",'event', null),
                        'data' => null
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

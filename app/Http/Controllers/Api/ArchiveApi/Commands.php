<?php

namespace App\Http\Controllers\Api\ArchiveApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Archive;
use App\Models\History;
use App\Models\ContentHeader;
use App\Models\ArchiveRelation;

class Commands extends Controller
{
    //
    public function createArchive(Request $request)
    {
        try{
            $slug = Generator::getSlugName($request->archive_name, "archive");
            $validator = Validation::getValidateArchive($request);
            $user_id = $request->user()->id;

            if ($validator->fails()) {
                $errors = $validator->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "archive",
                    'history_body' => "has created a archive called '".$request->archive_name."'"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return response()->json([
                        'status' => 'failed',
                        'result' => $errors,
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $archive = Archive::create([
                        'id' => Generator::getUUID(),
                        'slug_name' => $slug,
                        'archive_name' => $request->archive_name,
                        'archive_desc' => $request->archive_desc,
                        'created_by' => $user_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_by' => null,
                        'updated_at' => null
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $archive->id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Archive Created',
                        'data' => $archive
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

    public function addToArchive(Request $request){
        // This usecase doens't need to include history !
        try{
            $user_id = $request->user()->id;
            
            $relation = ArchiveRelation::create([
                'id' => Generator::getUUID(),
                'archive_id' => $request->archive_id,
                'content_id' => $request->content_id,
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Content Added to Archive',
                'data' => $relation
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editArchive(Request $request, $slug)
    {
        try{
            $user_id = $request->user()->id;
           
            $check = Archive::where('archive_name', $request->archive_name)
                ->where('created_by', $user_id)->get();

            if(count($check) == 0 || ($request->archive_name_old == $request->archive_name)){
                $validator = Validation::getValidateArchive($request);

                if ($validator->fails()) {
                    $errors = $validator->messages();
    
                    return response()->json([
                        'status' => 'failed',
                        'result' => $errors
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $data = new Request();
                    $obj = [
                        'history_type' => "archive",
                        'history_body' => "has updated '".$request->archive_name."' archive"
                    ];
                    $data->merge($obj);
    
                    $validatorHistory = Validation::getValidateHistory($data);
                    if ($validatorHistory->fails()) {
                        $errors = $validatorHistory->messages();
    
                        return response()->json([
                            'status' => 'failed',
                            'result' => $errors,
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    } else {
                        Archive::where('slug_name', $slug)->update([
                            'archive_name' => $request->archive_name,
                            'archive_desc' => $request->archive_desc,
                            'updated_at' => date("Y-m-d H:i"),
                            'updated_by' => $user_id
                        ]);

                        $result = Archive::where('slug_name', $slug)->first();

                        History::create([
                            'id' => Generator::getUUID(),
                            'history_type' => $data->history_type, 
                            'context_id' => $result->id, 
                            'history_body' => $data->history_body, 
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id
                        ]);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Archive successfully updated',
                            'data' => $result
                        ], Response::HTTP_OK);
                    }
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'result' => 'Archive name must be unique',
                    'data' => null
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteArchive(Request $request, $slug)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->user()->id;

            $data = new Request();
            $obj = [
                'history_type' => "archive",
                'history_body' => "has deleted '".$request->archive_name."' archive"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'result' => $errors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                DB::table("archives_relations")
                    ->join('archives', 'archives_relations.archive_id','=','archives.id')
                    ->where('slug_name', $slug)
                    ->where('archives_relations.created_by', $user_id)
                    ->delete();

                DB::table("archives")->where('slug_name', $slug)
                    ->delete();

                DB::table("histories")->insert([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Archive deleted'
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function multiActionArchiveRelation(Request $request, $slug){
        DB::beginTransaction();
        try{
            $user_id = $request->user()->id;
            $count_job = 0;
            $list_action = json_decode($request->list_relation);
            $cheader = ContentHeader::select('id')
                ->where('slug_name',$slug)
                ->first();

            if($list_action !== null || json_last_error() === JSON_ERROR_NONE){
                foreach($list_action as $la){
                    $rel = DB::table("archives_relations")
                        ->select('archives_relations.id')
                        ->join('archives','archives.id','=','archives_relations.archive_id')
                        ->where('content_id', $cheader->id)
                        ->where('archives.slug_name', $la->slug_name)
                        ->where('archives_relations.created_by', $user_id)
                        ->first();

                    if($la->check == 1 && $rel == null){
                        $arc = DB::table("archives")->select('id')
                            ->where('slug_name',$la->slug_name)
                            ->first();

                        DB::table("archives_relations")->insert([
                            'id' => Generator::getUUID(),
                            'archive_id' => $arc->id,
                            'content_id' => $cheader->id,
                            'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        $count_job++;
                    } else if($la->check == 0 && $rel != null){
                        DB::table("archives_relations")
                            ->where("id",$rel->id)
                            ->delete();
                        $count_job++;
                    } else {
                        // Check this shit
                    }
                }

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Added to Archive with '.$count_job.' changes',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'result' => 'Not a valid json array',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\ArchiveApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Generator;

use App\Models\Archive;
use App\Models\ArchiveRelation;

class Commands extends Controller
{
    //
    public function createArchive(Request $request)
    {
        //Helpers
        $slug = Generator::getSlugName($request->archive_name, "archive");

        try{
            $validator = Validator::make($request->all(), [
                'archive_name' => 'required',
                'archive_desc' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $archive = Archive::create([
                    'slug_name' => $slug,
                    'archive_name' => $request->archive_name,
                    'archive_desc' => $request->archive_desc,
                    'created_by' => 1, // for now, later will be changed to auth user
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_by' => null,
                    'updated_at' => null
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Archive Created',
                    'data' => $archive
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addToArchive(Request $request){
        try{
            $relation = ArchiveRelation::create([
                'archive_id' => $request->archive_id,
                'content_id' => $request->content_id,
                'created_by' => $request->user_id,
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

    public function editArchive(Request $request, $id)
    {
        try{
            //Validate name avaiability
            $check = Archive::where('archive_name', $request->archive_name)->where('id_user', $request->id_user)->get();

            if(count($check) == 0){
                $result = Archive::where('id', $id)->update([
                    'archive_name' => $request->archive_name,
                    'updated_at' => date("Y-m-d h:i")
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Archive successfully updated',
                    'data' => $result
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Archive name must be unique',
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
    
    public function deleteArchive(Request $request, $id)
    {
        try {
            $result = Archive::destroy($id);
        
            //Delete archive relation
            DB::table('archive_relations')
                ->where('archive_id', $id)
                ->where('user_id', $request->user_id)
                ->delete();
                
            return response()->json([
                'status' => 'success',
                'message' => 'Archive deleted'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Archive;
use App\Models\ArchiveRelation;

class ArchiveApi extends Controller
{
    //
    public function createArchive(Request $request)
    {

        function getSlugName($val){
            $check = Archive::select('slug_name')
                ->limit(1)
                ->get();

            if(count($check) > 0){
                $val = $val."_".date('mdhis');
            }

            $replace = str_replace("/","", stripslashes($val));
            $replace = str_replace(" ","_", $replace);
            $replace = str_replace("-","_", $replace);

            return strtolower($replace);
        }


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
                    'slug_name' => getSlugName($request->archive_name),
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

    public function getArchive($created_by) {
        try{
            $archive = Archive::select('slug_name', 'archive_name', 'archive_desc', 'created_by')
                ->where('created_by', $created_by)
                ->get();

            if ($archive->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Archive Found',
                    'data' => $archive
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Archive Not Found',
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

    public function addToArchive(Request $request){
        try{
            $relation = ArchiveRelation::create([
                'archive_id' => $request->archive_id,
                'content_id' => $request->content_id,
                'created_by' => 1, // for now, later will be changed to auth user
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
}

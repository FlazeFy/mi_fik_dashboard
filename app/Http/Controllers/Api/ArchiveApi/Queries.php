<?php

namespace App\Http\Controllers\Api\ArchiveApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Archive;

class Queries extends Controller
{
    public function getArchive(Request $request) 
    {
        try{
            $user_id = $request->user()->id;

            $archive = Archive::select('slug_name', 'archive_name', 'archive_desc')
                ->where('created_by', $user_id)
                ->orderBy('created_at', 'DESC')
                ->orderBy('updated_at', 'DESC')
                ->get();

            if ($archive->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Archive Found',
                    'data' => $archive
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
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
}

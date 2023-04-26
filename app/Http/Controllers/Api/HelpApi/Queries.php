<?php

namespace App\Http\Controllers\Api\HelpApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Help;

class Queries extends Controller
{
    public function getHelpCategoryByType($type){
        try{
            $help = Help::select('helps.id', 'help_type', 'help_category', 'help_body', 'helps.updated_at', 'username')
                ->leftJoin('admins', 'admins.id', '=', "helps.updated_by")
                ->where('help_type',$type)
                ->whereNotNull('help_category')
                ->orderBy('helps.created_at', 'DESC')
                ->paginate(10);

            if ($help->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Help category not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Help category found',
                    'data' => $help
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getHelpType(){
        try{
            $help = Help::select('help_type')
                ->whereNotNull('help_category')
                ->where('help_type', '!=', 'about')
                ->where('help_type', '!=', 'contact')
                ->orderBy('help_type', 'DESC')
                ->groupBy('help_type')
                ->get();

            if ($help->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Help type not found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Help type found',
                    'data' => $help
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

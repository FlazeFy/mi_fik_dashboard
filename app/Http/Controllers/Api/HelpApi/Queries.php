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
            $help = Help::select('id', 'help_type', 'help_category')
                ->where('help_type',$type)
                ->whereNotNull('help_category')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);

            if ($help->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Help category not found',
                    'data' => $help
                ], Response::HTTP_OK);
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
}

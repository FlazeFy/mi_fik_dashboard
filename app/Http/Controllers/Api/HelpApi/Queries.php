<?php

namespace App\Http\Controllers\Api\HelpApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PersonalAccessTokens;

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
                    'message' => Generator::getMessageTemplate("business_read_failed", 'help category', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'help category', null),
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

    public function getHelpType(Request $request){
        try{
            $user_id = $request->user()->id;
            $check = PersonalAccessTokens::where('tokenable_id', $user_id)->first();

            if($check->tokenable_type === "App\\Models\\User"){ // User
                $help = Help::select('help_type')
                    ->whereNotNull('help_category')
                    ->where('help_type', '!=', 'about')
                    ->where('help_type', '!=', 'contact')
                    ->orderBy('created_at', 'DESC')
                    ->orderBy('help_type', 'DESC')
                    ->groupBy('help_type')
                    ->get();
            } else {
                $help = Help::getHelpListNType();
            }

            if (count($help)==0) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'help type', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'help type', null),
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

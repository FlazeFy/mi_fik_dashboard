<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Generator;
use App\Models\Info;

class QueryInfo extends Controller
{
    public function getInfoPageAndLocation($page, $location) {
        try {

            $newPage = str_replace("-","/",$page);

            $info = Info::select('info_type', 'info_body', 'info_location')
                ->where('is_active', 1)
                ->where('info_location', $location)
                ->where('info_page', $newPage)
                ->first();

            if (is_null($info)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'info', null),
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'info', null),
                    'data' => $info
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

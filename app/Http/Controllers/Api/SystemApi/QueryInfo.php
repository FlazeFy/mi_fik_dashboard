<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Info;

class QueryInfo extends Controller
{
    //
    public function getAvailableInfoApi(Request $request) {

        $page = $request->page;
        $location = $request->location;

        $res = Info::select('info_type','info_body','info_location','is_active')
            ->where('is_active', 1)
            ->where('info_location', $location)
            ->where('info_page', $page)
            ->get();

        if ($res->isEmpty()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Info Not Found',
                'data' => $res
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Info Found',
                'data' => $res
            ], Response::HTTP_OK);
        }
    }
}

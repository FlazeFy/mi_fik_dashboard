<?php

namespace App\Http\Controllers\Api\ContentApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Converter;
use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Query;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\ContentViewer;
use App\Models\Task;

class QueryContent extends Controller
{
    public function getContentHeader()
    {
        try{
            $select = Query::getSelectTemplate("content_thumbnail");

            $content = ContentHeader::selectRaw($select)
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->orderBy('contents_headers.content_date_start', 'DESC')
                ->paginate(12);

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Not Found',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Header Found',
                    'data' => $content
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContentBySlug($slug)
    {
        try{
            $select = Query::getSelectTemplate("content_detail");

            $content = ContentHeader::selectRaw($select)
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                ->groupBy('contents_headers.id')
                ->where('slug_name', $slug)
                ->get();

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Content Not Found'
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Header Found',
                    'data' => $content
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContentBySlugLike($slug, $order, $date, $search)
    {
        $page = 12;

        try{
            $select = Query::getSelectTemplate("content_thumbnail");
            $search = trim($search);

            if($slug != "all"){
                $i = 1;
                $query = "";
                $filter_tag = explode(",", $slug);

                foreach($filter_tag as $ft){
                    $stmt = 'content_tag like '."'".'%"slug_name":"'.$ft.'"%'."'";

                    if($i != 1){
                        $query = substr_replace($query, " ".$stmt." OR", 0, 0);
                    } else {
                        $query = substr_replace($query, " ".$stmt, 0, 0);
                    }
                    $i++;
                }

                if($date != "all"){
                    $date = explode("_", $date);
                    $ds = $date[0];
                    $de = $date[1];
                    $filter_date = Query::getWhereDateTemplate($ds, $de);

                    $content = ContentHeader::selectRaw($select)
                        ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                        ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->where('is_draft', 0)
                        ->whereNull('deleted_at')
                        ->where('content_title', 'LIKE', '%' . $search . '%')
                        ->whereRaw($query)
                        ->whereRaw($filter_date)
                        ->paginate($page);
                } else {
                    $content = ContentHeader::selectRaw($select)
                        ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                        ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->where('is_draft', 0)
                        ->whereNull('deleted_at')
                        ->where('content_title', 'LIKE', '%' . $search . '%')
                        ->whereRaw($query)
                        ->paginate($page);
                }
            } else {
                if($date != "all"){
                    $date = explode("_", $date);
                    $ds = $date[0];
                    $de = $date[1];
                    $filter_date = Query::getWhereDateTemplate($ds, $de);

                    $content = ContentHeader::selectRaw($select)
                        ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                        ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->whereRaw($filter_date)
                        ->where('is_draft', 0)
                        ->whereNull('deleted_at')
                        ->where('content_title', 'LIKE', '%' . $search . '%')
                        ->paginate($page);
                } else {
                    $content = ContentHeader::selectRaw($select)
                        ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                        ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->where('is_draft', 0)
                        ->whereNull('deleted_at')
                        ->where('content_title', 'LIKE', '%' . $search . '%')
                        ->paginate($page);
                }
            }

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Not Found',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Header Found',
                    'data' => $content
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllContentSchedule(Request $request, $date){
        try{
            $select_content = Query::getSelectTemplate("content_schedule");
            $select_task = Query::getSelectTemplate("task_schedule");

            $content = ContentHeader::selectRaw($select_content)
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->whereRaw("date(`content_date_start`) = ?", $date)
                ->whereNull('deleted_at')
                ->orderBy('content_date_start', 'DESC');

            $schedule = Task::selectRaw($select_task)
                ->where('created_by', $request->user_id)
                ->whereRaw("date(`task_date_start`) = ?", $date)
                ->whereNull('deleted_at')
                ->orderBy('tasks.task_date_start', 'DESC')
                ->union($content)
                ->get();

            if ($schedule->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Not Found',
                    'data' => $schedule
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Found',
                    'data' => $schedule
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStatsMostViewedEvent(){
        try{
            $res= ContentDetail::getMostViewedEvent(7);


            if(count($res) > 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Statistic found',
                    'data' => $res
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Statistic not found',
                    'data' => null
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

<?php

namespace App\Http\Controllers\Api\ContentApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Converter;
use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Query;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
                    'status' => 'failed',
                    'message' => 'Content Not Found',
                    'data' => null
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

    public function getContentBySlug($slug)
    {
        try{
            $content = ContentHeader::getFullContentBySlug($slug);

            if (count($content) == 0) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content Not Found',
                    'data' => null
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
                        ->leftjoin('admins', 'admins.id', '=', 'contents_headers.created_by')
                        ->leftjoin('users', 'users.id', '=', 'contents_headers.created_by')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->where('is_draft', 0)
                        ->whereNull('contents_headers.deleted_at')
                        ->where('content_title', 'LIKE', '%' . $search . '%')
                        ->whereRaw($query)
                        ->whereRaw($filter_date)
                        ->paginate($page);
                } else {
                    $content = ContentHeader::selectRaw($select)
                        ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                        ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                        ->leftjoin('admins', 'admins.id', '=', 'contents_headers.created_by')
                        ->leftjoin('users', 'users.id', '=', 'contents_headers.created_by')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->where('is_draft', 0)
                        ->whereNull('contents_headers.deleted_at')
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
                        ->leftjoin('admins', 'admins.id', '=', 'admins.created_by')
                        ->leftjoin('users', 'users.id', '=', 'users.created_by')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->whereRaw($filter_date)
                        ->where('is_draft', 0)
                        ->whereNull('contents_headers.deleted_at')
                        ->where('content_title', 'LIKE', '%' . $search . '%')
                        ->paginate($page);
                } else {
                    $content = ContentHeader::selectRaw($select)
                        ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                        ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                        ->leftjoin('admins', 'admins.id', '=', 'contents_headers.created_by')
                        ->leftjoin('users', 'users.id', '=', 'contents_headers.created_by')
                        ->groupBy('contents_headers.id')
                        ->orderBy('contents_headers.content_date_start', $order)
                        ->where('is_draft', 0)
                        ->whereNull('contents_headers.deleted_at')
                        ->where('content_title', 'LIKE', '%' . $search . '%')
                        ->paginate($page);
                }
            }

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content Not Found',
                    'data' => null
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

    public function getFinishedContent($order, $search)
    {
        $page = 12;

        try{
            $select = Query::getSelectTemplate("content_thumbnail");
            $search = trim($search);
            
            $content = ContentHeader::selectRaw($select.",(DATEDIFF(content_date_end, now()) * -1) as days_passed")
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                ->leftjoin('admins', 'admins.id', '=', 'contents_headers.created_by')
                ->leftjoin('users', 'users.id', '=', 'contents_headers.created_by')
                ->groupBy('contents_headers.id')
                ->orderBy('days_passed', $order)
                ->whereNull('contents_headers.deleted_at')
                ->whereRaw('(DATEDIFF(content_date_end, now()) * -1) > 1')
                ->where('content_title', 'LIKE', '%' . $search . '%')
                ->paginate($page);

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content Not Found',
                    'data' => null
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

    public function getAllContentSchedule(Request $request, $date){
        try{
            $select_content = Query::getSelectTemplate("content_schedule");
            $select_task = Query::getSelectTemplate("task_schedule");
            $user_id = $request->user()->id;

            $content = ContentHeader::selectRaw($select_content)
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->whereRaw("date(`content_date_start`) = '".$date."'")
                //->whereRaw("date(`content_date_start`) <= '".$date."' AND date(`content_date_end`) >= '".$date."'")
                ->whereNull('deleted_at')
                ->orderBy('content_date_start', 'DESC');

            $schedule = Task::selectRaw($select_task)
                ->where('created_by', $user_id)
                ->whereRaw("date(`task_date_start`) = '".$date."'")
                //->whereRaw("date(`task_date_start`) <= '".$date."' AND date(`task_date_end`) >= '".$date."'")
                ->whereNull('deleted_at')
                ->orderBy('tasks.task_date_start', 'DESC')
                ->union($content)
                ->get();

            $clean = [];
            $total_content = 0;
            $total_task = 0;

            foreach ($schedule as $result) {
                $loc = json_decode($result->content_loc, true);
                $tag = json_decode($result->content_tag, true);
            
                $id = $result->id;
                $slug = $result->slug_name;
                $title = $result->content_title; 
                $desc = $result->content_desc;
                $date_start = $result->content_date_start; 
                $date_end = $result->content_date_end; 
                $from = $result->data_from; 

                $clean[] = [
                    'id' => $id,
                    'slug_name' => $slug,
                    'content_title' => $title,
                    'content_desc' => $desc,
                    'content_tag' => $tag,
                    'content_loc' => $loc,
                    'content_date_start' => $date_start,
                    'content_date_end' => $date_end,
                    'data_from' => $from
                ];

                if($from == 1){
                    $total_content++;
                } else {
                    $total_task++;
                }
            }

            $collection = collect($clean);
            $collection = $collection->sortBy('content_date_start')->values();
            $perPage = 12;
            $page = request()->input('page', 1);
            $paginator = new LengthAwarePaginator(
                $collection->forPage($page, $perPage),
                $collection->count(),
                $perPage,
                $page,
                ['path' => url()->current()]
            );
            $clean = $paginator->appends(request()->except('page'));

            if ($clean->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content Not Found',
                    'total' => [[
                        'content' => $total_content,
                        'task' => $total_task,
                    ]],
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Found',
                    'total' => [[
                        'content' => $total_content,
                        'task' => $total_task,
                    ]],
                    'data' => $clean
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

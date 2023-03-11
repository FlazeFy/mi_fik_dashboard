<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\ContentHeader;
use App\Models\ContentDetail;

class ContentApi extends Controller
{
    public function getContentHeader()
    {
        $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->orderBy('contents_headers.created_at', 'DESC')
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
    }

    public function getContentBySlug($slug)
    {
        $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
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
    }

    public function getContentBySlugLike($slug, $order)
    {
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

            $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->orderBy('contents_headers.created_at', $order)
                ->whereRaw($query)
                ->paginate(12);

        } else {
            $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->orderBy('contents_headers.created_at', $order)
                ->paginate(12);
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
    }

    public function getAllContentSchedule($date){
        $content = ContentHeader::selectRaw('slug_name, content_title, content_desc, content_loc, content_tag, content_date_start, content_date_end, 1 as data_from')
            ->whereRaw("date(`content_date_start`) = ?", $date)
            ->orderBy('content.content_date_start', 'DESC');
            
        $schedule = Task::selectRaw('slug_name, task_title as content_title, task_desc as content_desc, null as content_loc, null as content_tag, task_date_start as content_date_start, task_date_end as content_date_end, 2 as data_from')
            ->where('id_user', 1)
            ->whereRaw("date(`task_date_start`) = ?", $date)
            ->orderBy('task.task_date_start', 'DESC')
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
                'message' => 'Content Header Found',
                'data' => $schedule
            ], Response::HTTP_OK);
        }
    }

    public function deleteContent(Request $request, $id){
        try{
            $content = ContentHeader::where('id', $id)->update([
                'deleted_at' => date("Y-m-d h:i:s"),
                'deleted_by' => $request->user_id,
            ]);

            if($content != 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content created',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content not found',
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

    public function destroyContent($id){
        try{
            $content = ContentHeader::destroy($id);

            ContentDetail::where('content_id', $id)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Content permanentaly deleted',
                'data' => $content
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

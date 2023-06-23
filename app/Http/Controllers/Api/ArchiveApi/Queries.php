<?php

namespace App\Http\Controllers\Api\ArchiveApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Query;
use Illuminate\Support\Facades\DB;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Archive;
use App\Models\ContentHeader;
use App\Models\Task;

class Queries extends Controller
{
    public function getArchive(Request $request, $slug, $type) 
    {
        try{
            $user_id = $request->user()->id;
            if($slug != "%20" && trim($slug) != "" && trim($slug) != null && $type != "%20"){
                if($type == "Event"){
                    $select = " ,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM archives_relations ars
                            JOIN contents_headers chq ON chq.id = ars.content_id
                            WHERE ars.archive_id = a.id AND chq.slug_name = '".$slug."'
                        ) THEN 1 
                        ELSE 0
                    END AS found ";
                } else {
                    $select = " ,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM archives_relations ars
                            JOIN tasks tsk ON tsk.id = ars.content_id
                            WHERE ars.archive_id = a.id AND tsk.slug_name = '".$slug."'
                        ) THEN 1 
                        ELSE 0
                    END AS found ";
                }
            } else {
                $select = " , 0 as found";
            }

            $archive  = DB::select(DB::raw("
                SELECT a.slug_name, archive_name, archive_desc, COUNT(ch.id) AS total_event, COUNT(ts.id) AS total_task
                ".$select."
                FROM archives a
                LEFT JOIN archives_relations ar ON ar.archive_id = a.id
                LEFT JOIN contents_headers ch ON ch.id = ar.content_id
                LEFT JOIN tasks ts ON ts.id = ar.content_id
                WHERE a.created_by = '".$user_id."'
                AND ch.deleted_at is NULL
                AND ts.deleted_at is NULL
                GROUP BY 1
                ORDER BY ar.created_at DESC, a.updated_at DESC, a.created_at DESC
            "));    

            if (count($archive) > 0) {
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

    public function getContentByArchive(Request $request, $slug) 
    {
        try{
            $select_content = Query::getSelectTemplate("content_schedule");
            $select_properties = Query::getSelectTemplate("content_properties");
            $select_properties_null = Query::getSelectTemplate("content_properties_null");
            $select_task = Query::getSelectTemplate("task_schedule");
            $user_id = $request->user()->id;

            $content = ContentHeader::selectRaw('archives_relations.archive_id, contents_headers.created_at, contents_headers.updated_at, content_reminder, content_image, '.$select_content.', '.$select_properties)
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
                ->leftjoin('admins', 'admins.id', '=', 'contents_headers.created_by')
                ->leftjoin('users', 'users.id', '=', 'contents_headers.created_by')
                ->leftjoin('archives_relations', 'archives_relations.content_id', '=', 'contents_headers.id')
                ->leftjoin('archives', 'archives.id', '=', 'archives_relations.archive_id')
                ->where('archives.slug_name', $slug)
                ->where('archives_relations.created_by', $user_id)
                ->whereNull('contents_headers.deleted_at')
                ->groupBy('contents_viewers.content_id');

            $schedule = Task::selectRaw('archives_relations.archive_id, tasks.created_at, tasks.updated_at, task_reminder as content_reminder, null as content_image, '.$select_task.', '.$select_properties_null)
                ->leftjoin('archives_relations', 'archives_relations.content_id', '=', 'tasks.id')
                ->leftjoin('archives', 'archives.id', '=', 'archives_relations.archive_id')
                ->where('archives.slug_name', $slug)
                ->where('archives_relations.created_by', $user_id)
                ->whereNull('deleted_at')
                ->union($content)
                ->get();

            $clean = [];
            $total_content = 0;
            $total_task = 0;

            foreach ($schedule as $result) {
                if($result->id != null){ // I dont know why on earth i put this
                    $loc = json_decode($result->content_loc, true);
                    $tag = json_decode($result->content_tag, true);
                
                    $id = $result->id;
                    $slug = $result->slug_name;
                    $title = $result->content_title; 
                    $desc = $result->content_desc;
                    $date_start = $result->content_date_start; 
                    $date_end = $result->content_date_end; 
                    $from = $result->data_from; 
                    $createdAt = $result->created_at;
                    $updatedAt = $result->updated_at;
                    $reminder = $result->content_reminder;
                    $content_image = $result->content_image;
                    $auc = $result->admin_username_created;
                    $uuc = $result->user_username_created;
                    $aic = $result->admin_image_created;
                    $uic = $result->user_image_created;
                    $total = $result->total_views;

                    $clean[] = [
                        'id' => $id,
                        'slug_name' => $slug,
                        'content_title' => $title,
                        'content_desc' => $desc,
                        'content_tag' => $tag,
                        'content_loc' => $loc,
                        'content_date_start' => $date_start,
                        'content_date_end' => $date_end,
                        'content_image' => $content_image,
                        'data_from' => $from,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                        'content_reminder' => $reminder,
                        'admin_username_created' => $auc,
                        'user_username_created' => $uuc,
                        'admin_image_created' => $aic,
                        'user_image_created' => $aic,
                        'total_views' => $total
                    ];

                    if($from == 1){
                        $total_content++;
                    } else {
                        $total_task++;
                    }
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
}

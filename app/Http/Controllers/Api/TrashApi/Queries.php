<?php

namespace App\Http\Controllers\Api\TrashApi;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Helpers\Query;

use App\Models\Task;
use App\Models\ContentHeader;
use App\Models\PersonalAccessTokens;

class Queries extends Controller
{
    public function getAllContentTrash(Request $request, $order, $category, $search){
        try{
            $select_content = Query::getSelectTemplate("event_dump");
            $select_task = Query::getSelectTemplate("task_dump");
            $select_tag = Query::getSelectTemplate("tag_dump");
            $select_group = Query::getSelectTemplate("group_dump");
            $select_info = Query::getSelectTemplate("info_dump");
            $select_fbc = Query::getSelectTemplate("feedback_dump");
            $select_dct = Query::getSelectTemplate("dictionary_dump");

            $join_content = Query::getJoinTemplate("content_dump", "ch");
            $join_task = Query::getJoinTemplate("content_dump", "ts");
            $join_tag = Query::getJoinTemplate("content_dump", "tg");
            $join_group = Query::getJoinTemplate("content_dump", "ug");
            $join_info = Query::getJoinTemplate("tag", "inf");
            $join_dct = Query::getJoinTemplate("tag", "dct");

            $where_from = "WHERE ";
            $search = trim($search);
            
            if($category != "all"){
                $where_from = "WHERE data_from = ".$category." AND";
            } 

            $user_id = $request->user()->id;
            $check = PersonalAccessTokens::where('tokenable_id', $user_id)->first();
            if($check->tokenable_type === "App\\Models\\User"){ // User
                $content = DB::select(DB::raw("
                    SELECT * FROM (
                        SELECT 
                            ".$select_content." 
                        FROM contents_headers ch
                        JOIN contents_details cd ON ch.id = cd.content_id
                        ".$join_content."
                        WHERE ch.deleted_at IS NOT NULL
                    UNION
                        SELECT 
                            ".$select_task." 
                        FROM tasks ts
                        ".$join_task."
                        WHERE ts.deleted_at IS NOT NULL
                        AND ts.created_by = '".$user_id."'
                    ) as q ".$where_from." content_title LIKE '%".$search."%' ORDER BY deleted_at ".$order."
                "));      
            } else {
                $content = DB::select(DB::raw("
                    SELECT * FROM (
                        SELECT 
                            ".$select_content." 
                        FROM contents_headers ch
                        JOIN contents_details cd ON ch.id = cd.content_id
                        ".$join_content."
                        WHERE ch.deleted_at IS NOT NULL
                    UNION 
                        SELECT 
                            ".$select_tag." 
                        FROM tags tg
                        ".$join_tag."
                        JOIN dictionaries dt ON tg.tag_category = dt.slug_name
                        WHERE tg.deleted_at IS NOT NULL
                    UNION 
                        SELECT 
                            ".$select_group." 
                        FROM users_groups ug
                        ".$join_group."
                        LEFT JOIN groups_relations gr ON ug.id = gr.group_id
                        WHERE ug.deleted_at IS NOT NULL
                        GROUP BY ug.id
                    UNION 
                        SELECT 
                            ".$select_info." 
                        FROM infos inf
                        ".$join_info."
                        WHERE inf.deleted_at IS NOT NULL
                    UNION 
                        SELECT 
                            ".$select_fbc." 
                        FROM feedbacks fb
                        WHERE fb.deleted_at IS NOT NULL
                    UNION 
                        SELECT 
                            ".$select_dct." 
                        FROM dictionaries dct
                        WHERE dct.deleted_at IS NOT NULL
                    ) as q ".$where_from." content_title LIKE '%".$search."%' ORDER BY deleted_at ".$order."
                "));      
            }    

            $clean = [];
            foreach ($content as $result) {
                $loc = json_decode($result->content_loc, true);
                $tag = json_decode($result->content_tag, true);
                if (json_last_error() !== JSON_ERROR_NONE && !is_array($tag)) {
                    $tag = $result->content_tag;
                } 
            
                $slug = $result->slug_name;
                $title = $result->content_title; 
                $desc = $result->content_desc; 
                $au_created = $result->admin_username_created; 
                $uu_created = $result->user_username_created; 
                $ai_created = $result->admin_image_created; 
                $ui_created = $result->user_image_created; 
                $ai_deleted = $result->admin_image_deleted; 
                $ui_deleted = $result->user_image_deleted; 
                $au_updated = $result->admin_username_updated; 
                $uu_updated = $result->user_username_updated; 
                $au_deleted = $result->admin_username_deleted; 
                $uu_deleted = $result->user_username_deleted; 
                $date_start = $result->content_date_start; 
                $date_end = $result->content_date_end; 
                $created_at = $result->created_at; 
                $deleted_at = $result->deleted_at; 
                $from = $result->data_from; 

                $clean[] = [
                    'slug_name' => $slug,
                    'content_title' => $title,
                    'content_desc' => $desc,
                    'admin_username_created' => $au_created,
                    'user_username_created' => $uu_created,
                    'admin_username_updated' => $au_updated,
                    'user_username_updated' => $uu_updated,
                    'admin_username_deleted' => $au_deleted,
                    'user_username_deleted' => $uu_deleted,
                    'admin_image_created' => $ai_created,
                    'user_image_created' => $ui_created,
                    'admin_image_deleted' => $ai_deleted,
                    'user_image_deleted' => $ui_deleted,
                    'content_tag' => $tag,
                    'content_loc' => $loc,
                    'content_date_start' => $date_start,
                    'content_date_end' => $date_end,
                    'created_at' => $created_at,
                    'deleted_at' => $deleted_at,
                    'data_from' => $from
                ];
            }

            $collection = collect($clean);
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
            //$clean = array_values($clean['data']['data']);

            if (count($clean) == 0) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Found',
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

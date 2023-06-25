<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use App\Helpers\Query;
use App\Helpers\Generator;

use App\Models\User;

class ContentHeader extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'contents_headers';
    protected $primaryKey = 'id';
    protected $fillable = ['id','slug_name', 'content_title', 'content_desc', 'content_date_start', 'content_date_end', 'content_reminder', 'content_image', 'is_draft', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by', 'updated_by'];
    protected $casts = [
        'content_attach' => 'array',
        'content_tag' => 'array',
        'content_loc' => 'array'
    ];

    //Fix this stupid code LOL
    //Should be use IN() instead of "WHERE & OR"
    public static function getAllContentFilter($selected, $role){
        if($role != 1){
            $user_id = Generator::getUserIdV2(0);
            $user = User::where('id',$user_id)->first();
            $roles = $user->role;
            $arr_roles = "";
            $total = count($roles);
            for($i = 0; $i < $total; $i++){
                $end = "";
                if($i != $total - 1){
                    $end = "|";
                } 
                $arr_roles .= $roles[$i]['slug_name'].$end;
            }
            $based_role = "JSON_EXTRACT(content_tag, '$[*].slug_name') REGEXP '(".$arr_roles.")'";
        }

        $res = ContentHeader::select('slug_name','content_title','content_image','content_date_start','content_date_end','content_tag')
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->whereNull('contents_headers.deleted_at')
            ->where('is_draft', 0);

        if($role != 1){
            $res->whereRaw($based_role);
        }

        if($selected != "All"){
            $i = 1;
            $query = "";
            $filter_tag = $selected;
            
            foreach($filter_tag as $ft){
                //$stmt = "JSON_EXTRACT(replace(replace(content_tag, '[', ''), ']', ''), '$.slug_name') = '".$ft."'";
                $stmt = 'content_tag like '."'".'%"slug_name":"'.$ft.'"%'."'";

                if($i != 1){
                    $query = substr_replace($query, " ".$stmt." OR", 0, 0);
                } else {
                    $query = substr_replace($query, " ".$stmt, 0, 0);
                }
                $i++;
            }

            $res->whereRaw($query);  
        } 

        return $res->get();
    }   

    public static function getTotalContentByMonth($range){
        $res = ContentHeader::selectRaw("MONTH(created_at) as 'month', COUNT(*) as total")
            ->where('created_at', '>=', date("Y-m-d", strtotime("-".$range." months")))
            ->groupByRaw('MONTH(created_at)')
            ->get();
            
        return $res;
    } 

    public static function getFullContentBySlug($slug_name){
        $select_content = Query::getSelectTemplate("content_detail");
        $join_content = Query::getJoinTemplate("content_detail", "ch");

        $content = DB::select(DB::raw("
            SELECT 
                ".$select_content." 
            FROM contents_headers ch
            JOIN contents_details cd ON ch.id = cd.content_id
            ".$join_content."
            WHERE ch.deleted_at IS NULL
            AND ch.slug_name = '".$slug_name."'
            GROUP BY ch.id
            LIMIT 1
        ")); 

        if($content){
            $clean = [];
            $obj;
            foreach ($content as $result) {
                $loc = json_decode($result->content_loc, true);
                $tag = json_decode($result->content_tag, true);
                $att = json_decode($result->content_attach, true);
            
                $slug = $result->slug_name;
                $title = $result->content_title; 
                $desc = $result->content_desc; 
                $au_created = $result->admin_username_created; 
                $uu_created = $result->user_username_created; 
                $ai_created = $result->admin_image_created; 
                $ui_created = $result->user_image_created; 
                $au_updated = $result->admin_username_updated; 
                $uu_updated = $result->user_username_updated; 
                $au_deleted = $result->admin_username_deleted; 
                $uu_deleted = $result->user_username_deleted; 
                $image = $result->content_image; 
                $date_start = $result->content_date_start; 
                $date_end = $result->content_date_end; 
                $created_at = $result->created_at; 
                $updated_at = $result->updated_at; 
                $is_draft = $result->is_draft; 
                $views = $result->total_views; 
    
                $clean[] = (object)[
                    'slug_name' => $slug,
                    'content_title' => $title,
                    'content_desc' => $desc,
                    'admin_username_created' => $au_created,
                    'user_username_created' => $uu_created,
                    'admin_image_created' => $ai_created,
                    'user_image_created' => $ui_created,
                    'admin_username_updated' => $au_updated,
                    'user_username_updated' => $uu_updated,
                    'admin_username_deleted' => $au_deleted,
                    'user_username_deleted' => $uu_deleted,
                    'content_tag' => $tag,
                    'content_image' => $image,
                    'content_attach' => $att,
                    'content_loc' => $loc,
                    'content_date_start' => $date_start,
                    'content_date_end' => $date_end,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'is_draft' => $is_draft,
                    'total_views' => $views,
                ];
            }
            
            return $clean;
        } else {
            return null;
        }
    }

    public static function getContentIdBySlug($slug_name){
        $res = ContentHeader::select('id')
            ->where('slug_name', $slug_name)
            ->limit(1)
            ->get();

        foreach($res as $r){
            $id = $r->id;
        }

        return $id;
    }

    public static function getCountEngPostEvent($id){
        $res = ContentHeader::selectRaw('COUNT(1) as total')
            ->where('created_by', $id)
            ->groupBy('created_by')
            ->get();

        if(count($res) != null){
            foreach($res as $r){
                $res = $r->total;
            }
        } else {
            $res = 0;
        }

        return $res;
    }

    public static function getMyDraft($role, $user_id){
        $select = Query::getSelectTemplate("content_draft_homepage");

        if($role == 1){
            $res = ContentHeader::selectRaw($select)
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->leftjoin('admins', 'admins.id', '=', 'contents_headers.created_by')
                ->leftjoin('users', 'users.id', '=', 'contents_headers.created_by')
                ->orderBy('contents_headers.created_at', 'DESC')
                ->orderBy('contents_headers.updated_at', 'DESC')
                ->where('is_draft', 1)
                ->whereNull('contents_headers.deleted_at')
                ->get();
        } else {
            $res = ContentHeader::selectRaw($select)
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->leftjoin('admins', 'admins.id', '=', 'contents_headers.created_by')
                ->leftjoin('users', 'users.id', '=', 'contents_headers.created_by')
                ->orderBy('contents_headers.created_at', 'DESC')
                ->orderBy('contents_headers.updated_at', 'DESC')
                ->where('is_draft', 1)
                ->where('contents_headers.created_by', $user_id)
                ->whereNull('contents_headers.deleted_at')
                ->get();
        }   

        return $res;
    }
}

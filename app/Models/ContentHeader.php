<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Query;

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
    public static function getAllContentFilter($selected){
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

            $res = ContentHeader::select('slug_name','content_title','content_image','content_date_start','content_date_end','content_tag')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->whereRaw($query)
                ->get();

        } else {
            $res = ContentHeader::select('slug_name','content_title','content_image','content_date_start','content_date_end','content_tag')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->get();
        }

        return $res;
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

        // $obj = (object)$clean;
        return $clean;
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
}

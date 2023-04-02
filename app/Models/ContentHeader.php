<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Query;

class ContentHeader extends Model
{
    use HasFactory;
    //use HasUuids;

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
        $select = Query::getSelectTemplate("content_detail");

        $res = ContentHeader::selectRaw($select)
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->leftjoin('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
            ->groupBy('contents_headers.id')
            ->where('slug_name', $slug_name)
            ->limit(1)
            ->get();

        return $res;
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

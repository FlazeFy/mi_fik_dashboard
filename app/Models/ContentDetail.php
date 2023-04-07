<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\Query;

class ContentDetail extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'contents_details';
    protected $primaryKey = 'id';
    protected $fillable = ['id','content_id', 'content_attach', 'content_tag', 'content_loc', 'created_at', 'updated_at'];

    public static function getContentLocation(){
        $select = Query::getSelectTemplate("content_location");

        $res = ContentDetail::selectRaw($select)
            ->leftjoin('contents_headers', 'contents_headers.id', '=', 'contents_details.content_id')
            ->where('is_draft', 0)
            ->whereNot('content_loc', null)
            ->orderBy('content_date_start','DESC')
            ->get();

        return $res;
    }

    public static function getContentTag($id){
        $query = ContentDetail::select('content_tag')
            ->where('id', $id)
            ->limit(1)
            ->get();

        if(count($query) > 0){
            foreach($query as $q){
                $res = $q->content_tag;
            }
        } else {
            $res = null;
        }

        return $res;
    }

    //Statistic
    public static function getMostUsedTag(){
        //This query must directly return at least 10 most used tag
        $res = ContentDetail::select('content_tag')
            ->whereNot('content_tag', null)
            ->get();

        return $res;
    }

    public static function getMostUsedLoc(){
        $res = ContentDetail::select('content_loc')
            ->whereNot('content_loc', null)
            ->get();

        return $res;
    }

    public static function getMostViewedEvent($limit){
        $res = ContentHeader::selectRaw('contents_headers.id, content_title, count(1) as total')
            ->join('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
            ->groupBy('contents_headers.id')
            ->orderBy('total', 'DESC')
            ->limit($limit) 
            ->get();

        return $res;
    }

    public static function getMostViewedEventSeparatedRole($limit){
        $select = Query::getSelectTemplate("viewed_event_role");

        $res = ContentHeader::selectRaw($select)
            ->join('contents_viewers', 'contents_headers.id', '=', 'contents_viewers.content_id')
            ->join('users', 'users.id', '=', 'contents_viewers.created_by')
            ->groupBy('id_content','content_title')
            ->orderBy('total', 'DESC')
            ->limit($limit) 
            ->get();

        return $res;
    }
}

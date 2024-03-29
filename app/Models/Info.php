<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\Query;
use Illuminate\Support\Facades\DB;

class Info extends Model
{
    use HasFactory;
    //use HasUuids;

    public $incrementing = false;

    protected $table = 'infos';
    protected $primaryKey = 'id';
    protected $fillable = ['id','info_type', 'info_page', 'info_location', 'info_body', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function getAvailableInfo($page){
        $res = Info::select('info_type','info_body','info_location','is_active')
            ->where('is_active', 1)
            ->where('info_page', $page)
            ->get();

        return $res;
    }

    public static function getAllInfo($type){ 
        $join = Query::getJoinTemplate("tag", "inf");
        $select = Query::getSelectTemplate("info_manage");
        $where = "";

        if($type != "All"){
            $type = explode("_",$type);
            $where = "AND info_type = '".$type[1]."'";
        } 

        $res = DB::select(DB::raw("
            SELECT 
                ".$select." 
            FROM infos inf
            ".$join."
            WHERE inf.deleted_at IS NULL ".$where."
            ORDER BY CASE 
                WHEN inf.updated_at is not null then inf.updated_at 
                ELSE inf.created_at 
            END DESC
        "));     

        return $res;
    }
}

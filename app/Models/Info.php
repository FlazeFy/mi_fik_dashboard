<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;
    //use HasUuids;

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

    public static function getAllInfo(){
        $res = Info::select('info_type','info_body','info_location','is_active')
            ->orderBy('created_at', 'DESC')
            ->orderBy('updated_at', 'DESC')
            ->get();

        return $res;
    }
}

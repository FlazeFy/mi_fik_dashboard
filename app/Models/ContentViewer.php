<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ContentViewer extends Model
{
    use HasFactory;
    //use HasUuids;
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'contents_viewers';
    protected $primaryKey = 'id';
    protected $fillable = ['id','content_id', 'type_viewer', 'created_at', 'created_by'];

    public static function getViewByContentIdUserId($content_id, $user_id){
        $res = ContentViewer::select('id')
            ->where('content_id', $content_id)
            ->where('created_by', $user_id)
            ->limit(1)
            ->get();

        if(count($res) > 0){
            foreach($res as $r){
                return $r->id;
            }
        } else {
            return null;
        }
    }   
}

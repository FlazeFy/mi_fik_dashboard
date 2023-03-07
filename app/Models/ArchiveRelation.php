<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ArchiveRelation extends Model
{
    use HasFactory;
    use HasUuids;
    public $timestamps = false;

    protected $table = 'archives_relations';
    protected $primaryKey = 'id';
    protected $fillable = ['archive_id', 'content_id', 'created_at', 'created_by'];

    public static function getMyArchiveRelationBySlug($slug_name, $user_id){
        $res = ArchiveRelation::select('archives_relations.id','archive_id')
            ->rightjoin('archives', 'archives.id', '=', 'archives_relations.archive_id')
            ->leftjoin('contents_headers', 'contents_headers.id', '=', 'archives_relations.content_id')
            ->leftjoin('tasks', 'tasks.id', '=', 'archives_relations.content_id')
            ->whereRaw("
                    contents_headers.slug_name = '".$slug_name."'
                OR 
                    tasks.slug_name = '".$slug_name."'
            ")
            ->where('archives_relations.created_by', $user_id)
            ->orderBy('archives_relations.created_at', 'DESC')
            ->get();

        return $res;
    }
}

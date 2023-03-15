<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ContentDetail extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'contents_details';
    protected $primaryKey = 'id';
    protected $fillable = ['content_id', 'content_attach', 'content_tag', 'content_loc', 'created_at', 'updated_at'];

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'archives';
    protected $primaryKey = 'id';
    protected $fillable = ['id','slug_name', 'archive_name', 'archive_desc', 'created_at', 'updated_at', 'created_by', 'deleted_at'];

    public static function getMyArchive($user_id, $order){
        $res = Archive::select('id','slug_name','archive_name','archive_desc','created_at')
            ->where('created_by', $user_id)
            ->orderBy('created_at', $order)
            ->get();

        return $res;
    }
}

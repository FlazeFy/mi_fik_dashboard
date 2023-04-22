<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'users_requests';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'tag_slug_name', 'request_type', 'created_at', 'created_by', 'is_rejected', 'rejected_by', 'rejected_at', 'is_accepted', 'accepted_by', 'accepted_at'];

    protected $casts = [
        'tag_slug_name' => 'array'
    ];

    public static function getRecentlyRequest($id){
        $res = UserRequest::select('tag_slug_name', 'request_type', 'created_at')
            ->where('created_by', $id)
            ->where('is_accepted', 0)
            ->whereNull('is_rejected')
            ->limit(1)
            ->get();

        if(count($res) == 0){
            return null;
        } else {
            return $res;
        }
    }

    public static function getCountEngAccReq($id){
        $res = UserRequest::selectRaw('COUNT(1) as total')
            ->where('accepted_by', $id)
            ->groupBy('accepted_by')
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
}

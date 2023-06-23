<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Generator;

class History extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'histories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'history_type', 'context_id', 'history_body', 'history_send_to', 'created_at', 'created_by'];

    public static function getContentHistory($slug){
        $id = Generator::getContentId($slug);
        
        $res = History::select('history_body', 'history_type', 'history_send_to', 'histories.created_at', 'admins.username as admin_username', 'users.username as user_username', 
            'admins.image_url as admin_image', 'users.image_url as user_image')
            ->join('contents_headers', 'contents_headers.id', '=', 'histories.context_id')
            ->leftJoin('admins', 'admins.id', '=', 'histories.created_by')
            ->leftJoin('users', 'users.id', '=', 'histories.created_by')
            ->where('history_type', 'event')
            ->where('context_id', $id)
            ->orderBy('histories.created_at', 'DESC')
            ->get();

        return $res;
    }

    public static function getHistoryByType($type){
        if($type == "about" || $type == "help" || $type == "group" || $type == "tag" || $type == "info" || $type == "notification" || $type == "feedback" || $type == "contact"){
            $res = History::select('history_body', 'history_type', 'history_send_to', 'histories.created_at', 'admins.username as admin_username','admins.image_url as admin_image')
                ->leftJoin('admins', 'admins.id', '=', 'histories.created_by')
                ->where('history_type', $type)
                ->orderBy('histories.created_at', 'DESC')
                ->get();
        } else {
            $res = History::select('history_body', 'history_type', 'history_send_to', 'histories.created_at', 'admins.username as admin_username', 'users.username as user_username',
                'admins.image_url as admin_image', 'users.image_url as user_image')
                ->leftJoin('admins', 'admins.id', '=', 'histories.created_by')
                ->leftJoin('users', 'users.id', '=', 'histories.created_by')
                ->where('history_type', $type)
                ->orderBy('histories.created_at', 'DESC')
                ->get();
        }
       

        return $res;
    }
}

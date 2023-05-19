<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\Query;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Notification extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = ['id','notif_type', 'notif_body', 'notif_send_to', 'is_pending', 'pending_until', 'created_at', 'created_by', 'sended_at', 'sended_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    protected $casts = [
        'notif_send_to' => 'array',
    ];

    public static function getAllNotification(){
        $join = Query::getJoinTemplate("notif", "nt");
        $select = Query::getSelectTemplate("notif_manage");

        $res = DB::select(DB::raw("
            SELECT 
                ".$select." 
            FROM notifications nt
            ".$join."
            WHERE nt.deleted_at IS NULL
            ORDER BY nt.updated_at, nt.created_at DESC
        ")); 
        
        $clean = [];

        foreach ($res as $rs) {
            $send_to = json_decode($rs->notif_send_to, true);

            $id = $rs->id;
            $ntype = $rs->notif_type;
            $nbody = $rs->notif_body;
            $nst = $send_to;
            $isp = $rs->is_pending;
            $pu = $rs->pending_until;
            $createdAt = $rs->created_at;
            $sendedAt = $rs->sended_at;
            $updatedAt = $rs->updated_at;
            $deletedAt = $rs->deleted_at;
            $au_created = $rs->admin_username_created; 
            $au_updated = $rs->admin_username_updated; 
            $au_deleted = $rs->admin_username_deleted; 
            $au_sended = $rs->admin_username_sended; 
            $ai_created = $rs->admin_image_created; 
            $ai_updated = $rs->admin_image_updated; 
            $ai_deleted = $rs->admin_image_deleted; 
            $ai_sended = $rs->admin_image_sended; 

            $clean[] = [
                'id' => $id,
                'notif_type' => $ntype,
                'notif_body' => $nbody,
                'notif_send_to' => $nst,
                'is_pending' => $isp,
                'pending_until' => $pu,
                'created_at' => $createdAt,
                'sended_at' => $sendedAt,
                'updated_at' => $updatedAt,
                'deleted_at' => $deletedAt,
                'admin_username_created' => $au_created,
                'admin_username_updated' => $au_updated,
                'admin_username_deleted' => $au_deleted,
                'admin_username_sended' => $au_sended,
                'admin_image_created' => $ai_created,
                'admin_image_updated' => $ai_updated,
                'admin_image_deleted' => $ai_deleted,
                'admin_image_sended' => $ai_sended
            ];
        }

        $collection = collect($clean);
        $collection = $collection->sortBy('created_at')->values();

        return $collection;
    }

    public static function getCountEngPostNotif($id){
        $res = Notification::selectRaw('COUNT(1) as total')
            ->where('created_by', $id)
            ->groupBy('created_by')
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

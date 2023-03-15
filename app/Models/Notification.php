<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = ['notif_type', 'notif_body', 'notif_send_to', 'is_pending', 'pending_until', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function getAllNotification($order_1, $order_2){
        $res = Notification::select('*')
            ->orderBy('updated_at', $order_1)
            ->orderBy('created_at', $order_2)
            ->get();

        return $res;
    }
}

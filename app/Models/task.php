<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'slug_name', 'task_title', 'task_desc', 'task_date_start', 'task_date_end', 'task_reminder', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function getCountEngTask($id){
        $res = Task::selectRaw('COUNT(1) as total')
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
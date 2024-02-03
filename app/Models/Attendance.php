<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AttendanceRelation;

class Attendance extends Model
{
    use HasFactory;
    //use HasUuids;
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'attendance';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'content_id', 'attendance_title', 'attendance_desc', 'is_view', 'attendance_time_start', 'attendance_time_end', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public static function getAllAttendanceHeaders($role, $user_id){
        $res = Attendance::select('attendance_relations.id as id', 'attendance.id as id_attendance', 'contents_headers.content_title', 'attendance_title', 'attendance_time_start', 'attendance_relations.attendance_answer')
            ->leftjoin('contents_headers','contents_headers.id','=','attendance.content_id')
            ->leftjoin('attendance_relations','attendance.id','=','attendance_relations.attendance_id')
            ->leftjoin('admins', 'admins.id', '=', 'attendance.created_by')
            ->leftjoin('users', 'users.id', '=', 'attendance.created_by')
            ->orderBy('attendance.created_at', 'ASC')
            ->orderBy('attendance_title', 'DESC');

        if($role != 1){
            $res = $res->where('attendance_relations.users_id', $user_id);
        } else {
            $res = $res->groupBy('attendance.id');
        }

        return $res->get();
    }

    public static function getAttendanceDetail($id){
        $res = Attendance::select('attendance.id as id', 'contents_headers.slug_name', 'contents_headers.content_title', 'attendance_title', 'attendance_desc', 'is_view', 'attendance_time_start',
            'attendance_time_end', 'attendance.created_at', 'attendance_relations.created_at as answered_at', 'users.username as created_by_user', 'admins.username as created_by_admin', 'attendance_relations.attendance_answer')
            ->leftjoin('contents_headers','contents_headers.id','=','attendance.content_id')
            ->leftjoin('attendance_relations','attendance.id','=','attendance_relations.attendance_id')
            ->leftjoin('admins', 'admins.id', '=', 'attendance.created_by')
            ->leftjoin('users', 'users.id', '=', 'attendance.created_by')
            ->where("attendance.id",$id)
            ->first();

        return $res;
    }

    public static function getAttendanceResponse($id){    
        $res = AttendanceRelation::select('attendance_relations.created_at as answered_at','attendance_answer','users.username as user_username','users.image_url as user_image')
            ->leftjoin('attendance', 'attendance.id', '=', 'attendance_relations.attendance_id')
            ->leftjoin('users', 'users.id', '=', 'attendance_relations.users_id')
            ->orderby('attendance_relations.created_at','DESC')
            ->where("attendance.id",$id);

        return $res->get();
    }
}

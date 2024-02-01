<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    //use HasUuids;
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'attendance';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'content_id', 'attendance_title', 'attendance_desc', 'is_view', 'attendance_time_start', 'attendance_time_end', 'created_at', 'created_by', 'updated_at', 'updated_by'];
}

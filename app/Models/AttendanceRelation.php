<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRelation extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'attendance_relations';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'archive_id', 'content_id', 'created_at', 'created_by'];

}

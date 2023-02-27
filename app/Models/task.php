<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'task_title', 'task_desc', 'task_reminder', 'task_date_start', 'task_date_end', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
}
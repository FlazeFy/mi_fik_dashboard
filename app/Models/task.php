<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';
    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'task_title', 'task_desc', 'task_date_start', 'task_date_end', 'created_at', 'updated_at'];
}
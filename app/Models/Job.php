<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'type', 'status', 'payload', 'created_at', 'faced_by', 'fixed_at'];
}

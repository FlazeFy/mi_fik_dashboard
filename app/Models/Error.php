<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'errors';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'message', 'stack_trace', 'file', 'line', 'faced_by', 'created_at', 'fixed_at'];
}

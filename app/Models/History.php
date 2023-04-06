<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'histories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'history_type', 'context_id', 'history_body', 'history_send_to', 'created_at', 'created_by'];
}

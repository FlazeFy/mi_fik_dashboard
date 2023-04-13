<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'feedbacks';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'feedback_body', 'feedback_rate', 'feedback_suggest', 'created_at', 'deleted_at'];
}

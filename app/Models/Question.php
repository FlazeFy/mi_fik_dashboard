<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'question_type', 'question_body', 'question_answer', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];
}

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

    public static function getQuestionByUserId($id){
        $res = Question::selectRaw('questions.id, question_type, question_body, question_answer, questions.created_at, questions.updated_at')
            ->join('users', 'users.id', '=', 'questions.created_by')
            ->where('created_by', $id)
            ->orderBy('questions.created_at', 'DESC')
            ->orderBy('questions.updated_at', 'DESC')
            ->get();

        return $res;
    }
}

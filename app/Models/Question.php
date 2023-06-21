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
    protected $fillable = ['id', 'question_type', 'question_body', 'question_answer', 'is_active' ,'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function getQuestionByUserId($id){
        $res = Question::selectRaw('questions.id, question_type, question_body, question_answer, questions.created_at, questions.updated_at')
            ->join('users', 'users.id', '=', 'questions.created_by')
            ->where('created_by', $id)
            ->whereNull('questions.deleted_at')
            ->orderBy('questions.created_at', 'DESC')
            ->orderBy('questions.updated_at', 'DESC')
            ->get();

        $clean = [];
        foreach($res as $r){
            $clean[] = [
                'id' => "Q_".$r->id,
                'question_type' => $r->question_type,
                'question_from' => "me",
                'msg_reply' => null,
                'msg_body' => $r->question_body,
                'created_at' => $r->created_at
            ];

            if($r->updated_at){
                $clean[] = [
                    'id' => "A_".$r->id,
                    'question_type' => $r->question_type,
                    'question_from' => "them",
                    'msg_reply' => $r->question_body,
                    'msg_body' => $r->question_answer,
                    'created_at' => $r->updated_at
                ];
            }
        }

        $collection = collect($clean);
        $collection = $collection->sortBy('created_at')->values();

        return $collection;
    }

    public static function getCountEngQuestionAnswer($id){
        $res = Question::selectRaw('COUNT(1) as total')
            ->where('updated_by', $id)
            ->groupBy('updated_by')
            ->get();

        if(count($res) != null){
            foreach($res as $r){
                $res = $r->total;
            }
        } else {
            $res = 0;
        }

        return $res;
    }

    public static function getActiveFAQ(){
        $limit = SettingSystem::getLimitFAQ();

        $res = Question::select('question_body','question_answer')
            ->whereNotNull('question_answer')
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('updated_at','DESC')
            ->inRandomOrder()
            ->take($limit)
            ->get();

        return $res;
    }
}

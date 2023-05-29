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

    public static function getAllFeedbackSuggestion(){
        $res = Feedback::selectRaw('dct_name as category, count(1) as total')
            ->join('dictionaries', 'dictionaries.slug_name', '=', 'feedbacks.feedback_suggest')
            ->whereNull('feedbacks.deleted_at')
            ->groupBy('category')
            ->orderBy('total', 'DESC')
            ->get();

        return $res;
    }

    public static function getAllFeedback($limit, $suggest){
        $feedback = Feedback::selectRaw('feedbacks.id, feedback_body, feedback_rate, dct_name as type, feedbacks.created_at')
            ->join('dictionaries', 'dictionaries.slug_name', '=', 'feedbacks.feedback_suggest')
            ->whereNull('feedbacks.deleted_at');
  
        if($suggest != "All"){
            $feedback->where("feedback_suggest", $suggest);
        }

        $res = $feedback->orderBy('feedbacks.created_at', 'DESC')
            ->limit($limit)
            ->get();

        return $res;
    }

    public static function getRandomFeedback(){
        $limit = SettingSystem::getLimitFeedback();

        $res = Feedback::selectRaw('feedback_body, feedback_rate, substr(feedback_suggest,10) as feedback_suggest, created_at')
            ->whereNull('deleted_at')
            ->where('feedback_rate', '>=', 4)
            ->inRandomOrder()
            ->take($limit)
            ->get();

        return $res;
    }
}

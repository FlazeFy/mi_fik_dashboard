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
            ->groupBy('category')
            ->orderBy('total', 'DESC')
            ->get();

        return $res;
    }
}

<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Rules\TwoTimeFormats;
use App\Rules\TypeHistory;
use App\Rules\TypeInfo;
use App\Rules\TypeDictionary;
use App\Rules\TypeSuggest;
use App\Rules\TypeQuestion;
use Illuminate\Support\Str;

class Validation
{
    public static function getValidateJSON($json){
        $array = json_decode($json, true);

        if ($array !== null) {
            return true;
        } else {
            return false;
        }
    }

    public static function getValidateLogin($request){
        return Validator::make($request->all(), [
            'username' => 'required|min:6|max:30|string',
            'password' => 'required|min:6|string'
        ]);
    }

    public static function getValidateRegister($request){
        return Validator::make($request->all(), [
            'username' => 'required|min:6|max:30|string',
            'password' => 'required|min:6|string',
            'email' => 'required|string|email',
            'first_name' => 'required|string|max:35',
            'last_name' => 'nullable|string|max:35'
        ]);
    }

    public static function getValidateEvent($request){
        return Validator::make($request->all(), [
            'content_title' => 'required|min:6|max:75|string',
            'content_desc' => 'nullable|string|max:10000',
            'content_date_start' => 'required|date_format:Y-m-d',
            'content_date_end' => 'required|date_format:Y-m-d',
            'content_time_start' => ['required', new TwoTimeFormats],
            'content_time_end' => ['required', new TwoTimeFormats],
            'content_reminder' => 'required|string|max:75',
            //'content_image' => 'nullable|string|max:255' //Check this shit
        ]);
    }

    public static function getValidateEventDate($request){
        return Validator::make($request->all(), [
            'content_date_start' => 'required|date_format:Y-m-d',
            'content_date_end' => 'required|date_format:Y-m-d',
            'content_time_start' => ['required', new TwoTimeFormats],
            'content_time_end' => ['required', new TwoTimeFormats],
        ]);
    }

    public static function getValidateEventInfo($request){
        return Validator::make($request->all(), [
            'content_title' => 'required|min:6|max:75|string',
            'content_desc' => 'nullable|string|max:10000'
        ]);
    }

    public static function getValidateArchive($request){
        return Validator::make($request->all(), [
            'archive_name' => 'required|min:2|max:75|string',
            'archive_desc' => 'nullable|string|max:255'
        ]);
    }

    public static function getValidateTask($request){
        return Validator::make($request->all(), [
            'task_title' => 'required|min:6|max:75|string',
            'task_desc' => 'nullable|string|max:10000',
            'task_date_start' => 'nullable|date_format:Y-m-d',
            'task_date_end' => 'nullable|date_format:Y-m-d',
            'task_time_start' => ['required', new TwoTimeFormats],
            'task_time_end' => ['required', new TwoTimeFormats],
            'task_reminder' => 'required|string|max:75'
        ]);
    }

    public static function getValidateTaskV2($request){
        return Validator::make($request->all(), [
            'task_title' => 'required|min:6|max:75|string',
            'task_desc' => 'nullable|string|max:10000',
            'task_date_start' => 'nullable|date_format:Y-m-d H:i',
            'task_date_end' => 'nullable|date_format:Y-m-d H:i',
            'task_reminder' => 'required|string|max:75'
        ]);
    }

    public static function getValidateSetting($request){
        return Validator::make($request->all(), [
            'MOT_range' => 'required|numeric|max:10|min:3',
            'MOL_range' => 'required|numeric|max:10|min:3',
            'CE_range' => 'required|numeric|in:6,12',
            'MVE_range' => 'required|numeric|max:16|min:3'
        ]);
    }

    public static function getValidateJobs($request){
        return Validator::make($request->all(), [
            'DCD_range' => 'required|numeric|max:100|min:7',
            'DTD_range' => 'required|numeric|max:100|min:7',
            'DHD_range' => 'required|numeric|max:100|min:7',
        ]);
    }

    public static function getValidateLanding($request){
        return Validator::make($request->all(), [
            'FAQ_range' => 'required|numeric|max:99|min:4',
            'FBC_range' => 'required|numeric|max:99|min:3',
        ]);
    }

    public static function getValidateTag($request, $type){
        if($type == "desc"){
            return Validator::make($request->all(), [
                'tag_desc' => 'nullable|max:255|string',
            ]);
        } else if($type == "cat"){
            return Validator::make($request->all(), [
                'tag_category' => 'required|max:75|string',
            ]);
        } else if($type == "all"){
            return Validator::make($request->all(), [
                'tag_name' => 'required|max:30|string',
                'tag_desc' => 'nullable|max:255|string',
                'tag_category' => 'required|max:75|string'
            ]);
        } else if($type == "dct"){
            return Validator::make($request->all(), [
                'dct_name' => 'required|max:35|string',
                'dct_desc' => 'nullable|max:255|string'
            ]);
        }
    }

    public static function getValidateHistory($request){
        return Validator::make($request->all(), [
            'history_type' => ['required', new TypeHistory],
            'history_body' => 'required|min:6|max:255|string',
        ]);
    }

    public static function getValidateAboutApp($request){
        return Validator::make($request->all(), [
            'help_body' => 'required|string|max:7500|min:3',
        ]);
    }

    public static function getValidateAboutContact($request){
        return Validator::make($request->all(), [
            'instagram' => 'required|min:3|max:75|string',
            'whatsapp' => 'required|min:8|max:14|string',
            'twitter' => 'required|min:3|max:75|string',
            'address' => 'required|min:3|max:255|string',
            'email' => 'required|min:10|max:75|string',
        ]);
    }

    public static function getValidateHelp($request){
        return Validator::make($request->all(), [
            'help_type' => 'required|min:2|max:75|string',
            'help_category' => 'nullable|min:2|max:75|string',
            'help_body' => 'nullable|max:2500|string',
        ]);
    }

    public static function getValidateBodyTypeEdit($request){
        return Validator::make($request->all(), [
            'help_category' => 'nullable|min:2|max:75|string',
            'help_body' => 'nullable|max:2500|string',
        ]);
    }

    public static function getValidateInfoType($request){
        return Validator::make($request->all(), [
            'info_type' => ['required', new TypeInfo],
        ]);
    }

    public static function getValidateInfoCreate($request){
        return Validator::make($request->all(), [
            'info_type' => ['required', new TypeInfo],
            'info_page' => 'required|max:75|string',
            'info_location' => 'required|max:75|string',
            'info_body' => 'required|max:500|string',
        ]);
    }

    public static function getValidateDictionaryType($request){
        return Validator::make($request->all(), [
            'dct_type' => ['required', new TypeDictionary],
        ]);
    }

    public static function getValidateDictionaryInfo($request){
        return Validator::make($request->all(), [
            'dct_name' => 'required|min:2|max:75|string',
            'dct_desc' => 'nullable|min:2|max:255|string',
        ]);
    }

    public static function getValidateDictionary($request){
        return Validator::make($request->all(), [
            'dct_name' => 'required|min:2|max:75|string',
            'dct_desc' => 'nullable|min:2|max:255|string',
            'dct_type' => ['required', new TypeDictionary],
        ]);
    }

    public static function getValidateInfoBody($request){
        return Validator::make($request->all(), [
            'info_body' => 'nullable|min:2|max:500|string',
        ]);
    }

    public static function getValidateInfoPageLoc($request){
        return Validator::make($request->all(), [
            'info_page' => 'required|max:75|string',
            'info_location' => 'required|max:75|string',
        ]);
    }

    public static function getValidateEditProfile($request, $role){
        if($role == "admin"){
            return Validator::make($request->all(), [
                'first_name' => 'required|min:2|max:35|string',
                'last_name' => 'nullable|min:2|max:35|string',
                'password' => 'required|min:6|max:50|string',
                'phone' => 'required|min:9|max:14|string',
            ]);
        } else {
            return Validator::make($request->all(), [
                'first_name' => 'required|min:2|max:35|string',
                'last_name' => 'nullable|min:2|max:35|string',
                'password' => 'required|min:6|max:50|string',
            ]);
        }
    }

    public static function getValidateEditProfileImage($request){
        return Validator::make($request->all(), [
            'image_url' => 'nullable|max:255|url',
        ]);
    }

    public static function getValidateFeedbackCreate($request){
        return Validator::make($request->all(), [
            'feedback_body' => 'required|min:2|max:255|string',
            'feedback_rate' => 'required|numeric|min:1|max:5',
            'feedback_suggest' => ['required', new TypeSuggest],
        ]);
    }

    public static function getValidateAnswerFaq($request){
        return Validator::make($request->all(), [
            'question_answer' => 'required|min:2|max:500|string',
        ]);
    }

    public static function getValidateQuestionFaq($request){
        return Validator::make($request->all(), [
            'question_body' => 'required|min:2|max:255|string',
            'question_type' => ['required', new TypeQuestion],
        ]);
    }

    public static function getValidateGroup($request){
        return Validator::make($request->all(), [
            'group_name' => 'required|min:3|max:35|string',
            'group_desc' => 'nullable|min:3|max:255|string',
        ]);
    }

    public static function getValidateFirebaseToken($request){
        return Validator::make($request->all(), [
            'firebase_fcm_token' => 'required|max:255|string',
        ]);
    }

    public static function getValidateUserStarter($request){
        return Validator::make($request->all(), [
            'username' => 'required|min:6|max:30|string',
            'email' => ['required', 'min:11', 'max:75', 'string', 'email', function ($attr, $val, $err) {
                if (!Str::endsWith($val, '@gmail.com')) {
                    $err('The '.$attr.' must be a valid Gmail address');
                }
            }],
        ]);
    }

    public static function getValidateUserRegister($request){
        return Validator::make($request->all(), [
            'username' => 'required|min:6|max:30|string',
            'first_name' => 'required|min:2|max:35|string',
            'last_name' => 'nullable|min:2|max:35|string',
            'password' => 'required|min:6|max:50|string',
            'valid_until' => 'required|numeric', // Make this year for 6 year after & before
            'email' => ['required', 'min:11', 'max:75', 'string', 'email', function ($attr, $val, $err) {
                if (!Str::endsWith($val, '@gmail.com')) {
                    $err('The '.$attr.' must be a valid Gmail address');
                }
            }],
        ]);
    }
}

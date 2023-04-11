<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Rules\TwoTimeFormats;
use App\Rules\TypeHistory;

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

    public static function getValidateEventInfo($request){ 
        return Validator::make($request->all(), [
            'content_title' => 'required|min:6|max:75|string',
            'content_desc' => 'nullable|string|max:10000'
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
        ]);
    }

    public static function getValidateTag($request){ 
        return Validator::make($request->all(), [
            'tag_name' => 'required|min:2|max:30|string',
            'tag_desc' => 'nullable|max:255|string',
        ]);
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

    public static function getValidateHelp($request){ 
        return Validator::make($request->all(), [
            'help_type' => 'required|min:2|max:75|string',
            'help_category' => 'nullable|min:2|max:75|string',
            'help_body' => 'nullable|max:2500|string',
        ]);
    }
}
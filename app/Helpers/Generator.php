<?php
namespace App\Helpers;

use App\Models\ContentHeader;
use App\Models\Archive;
use App\Models\Task;
use App\Models\Tag;

use DateTime;

class Generator
{
    // Get slug name from content title, username, etc.
    // In : Leonardho R Sitanggang-01/02
    // Out : leonardho_r_sitanggang_0102
    public static function getSlugName($val, $type){ 
        $replace = str_replace(" ","_", $val);
        $replace = str_replace("-","_", $replace);
        $replace = preg_replace('/[!:\\\[\/"`;.\'^£$%&*()}{@#~?><>,|=+¬\]]/', '', $replace);

        if($type == "content"){
            $check = ContentHeader::select('slug_name')
                ->where('slug_name', $replace)
                ->limit(1)
                ->get();
        } else if($type == "archive"){
            $check = Archive::select('slug_name')
                ->where('slug_name', $replace)
                ->limit(1)
                ->get();
        } else if($type == "task"){
            $check = Task::select('slug_name')
                ->where('slug_name', $replace)
                ->limit(1)
                ->get();
        } else if($type == "tag"){
            $check = Tag::select('slug_name')
                ->where('slug_name', $replace)
                ->limit(1)
                ->get();
        }

        if(count($check) > 0){
            $replace = $replace."_".date('mdhis'); 
        }

        return strtolower($replace);
    }

    // Array to store month. First month is the current month.
    public static function getMonthList($max, $type){
        $date = new DateTime(date("Y/m/d")); 

        if($type == "number"){
            // Out : ['01', '02', '03']
            $arr = [$date->format('m')];

            for ($i = 1; $i < $max; $i++) {
                $date->modify("-1 month");
                array_push($arr, $date->format('m'));
            }
            
        } else if($type == "name"){
            // Out : ['Jan', 'Feb', 'Mar']
            $arr = ["'".substr($date->format('F'), 0, 3)."', ", ];

            for ($i = 1; $i < $max; $i++) {
                $date->modify("-1 month");
                array_push($arr, "'".substr($date->format('F'), 0, 3)."',");
            }
        }
        
        return $arr;
    }

    public static function getRandomReminder(){
        //Get random reminder
        $collection = [
            'reminder_1_day_before',
            'reminder_3_day_before',
            'reminder_none',
            'reminder_1_hour_before',
            'reminder_3_hour_before'
        ];
        $i = rand(0, 4);
        $reminder = $collection[$i];

        return $reminder;
    }
}
<?php
namespace App\Helpers;

use App\Models\ContentHeader;
use App\Models\Archive;
use App\Models\Task;
use App\Models\Tag;
use App\Models\Admin;
use App\Models\User;

use DateTime;

class Generator
{
    // Get slug name from content title, username, etc.
    // In : Leonardho R Sitanggang-01/02
    // Out : leonardho_r_sitanggang_0102
    public static function getSlugName($val, $type){ 
        $replace = str_replace(" ","-", $val);
        $replace = str_replace("_","-", $replace);
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

    public static function getUserId($slug_name, $role){
        if($role == 0){
            //for dashboard login and content views
            $query = Admin::select('id')
                ->where('slug_name', $slug_name)
                ->limit(1)
                ->get();
        } else if($role == 1) {
            //for dashboard login and content views
            $query = User::select('id')
                ->where('slug_name', $slug_name)
                ->whereRaw("role like '%dosen%' or '%staff%'")
                ->limit(1)
                ->get();
        } else if($role == 2) {
            //for content views only
            $query = User::select('id')
                ->where('slug_name', $slug_name)
                ->limit(1)
                ->get();
        }

        if(count($query) > 0){
            foreach($query as $q){
                $res = $q->id;
            }
        } else {
            $res = null;
        }

        return $res;
    }

    public static function getContentId($slug_name){
        $query = ContentHeader::select('id')
            ->where('slug_name', $slug_name)
            ->limit(1)
            ->get();

        if(count($query) > 0){
            foreach($query as $q){
                $res = $q->id;
            }
        } else {
            $res = null;
        }

        return $res;
    }

    public static function getGreeting($datetime){
        $hour = date('h', $datetime);

        if ($hour >= 3 && $hour <= 12) {
            $greet = "Good Morning";
        } else if ($hour > 12 && $hour <= 17) {
            $greet = "Good Evening";
        } else if (($hour > 17 && $hour <= 24) || ($hour >= 0 && $hour < 3)) {
            $greet = "Good Night";
        }

        return $greet;
    }

    public static function getUUID(){
        $result = '';
        $bytes = random_bytes(16);
        $hex = bin2hex($bytes);
        $time_low = substr($hex, 0, 8);
        $time_mid = substr($hex, 8, 4);
        $time_hi_and_version = substr($hex, 12, 4);
        $clock_seq_hi_and_reserved = hexdec(substr($hex, 16, 2)) & 0x3f;
        $clock_seq_low = hexdec(substr($hex, 18, 2));
        $node = substr($hex, 20, 12);
        $uuid = sprintf('%s-%s-%s-%02x%02x-%s', $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $clock_seq_low, $node);
        
        return $uuid;
    }
}
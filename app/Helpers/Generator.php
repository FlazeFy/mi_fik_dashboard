<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\Archive;
use App\Models\Task;
use App\Models\Tag;
use App\Models\Admin;
use App\Models\User;
use App\Models\Dictionary;
use App\Models\UserGroup;

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
        } else if($type == "dct_tag"){
            $check = Dictionary::select('slug_name')
                ->where('slug_name', $replace)
                ->where('dct_type', 'TAG-001')
                ->limit(1)
                ->get();
        } else if($type == "dct"){
            $check = Dictionary::select('slug_name')
                ->where('slug_name', $replace)
                ->limit(1)
                ->get();
        } else if($type == "group"){
            $check = UserGroup::select('slug_name')
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

    public static function getUserId($username, $role){
        if($role == 0){
            //for dashboard login and content views
            $query = Admin::select('id')
                ->where('username', $username)
                ->limit(1)
                ->get();
        } else if($role == 1) {
            //for dashboard login and content views
            $query = User::select('id')
                ->where('username', $username)
                ->whereRaw("role like '%lecturer%' or '%staff%'")
                ->limit(1)
                ->get();
        } else if($role == 2) {
            //for content views only
            $query = User::select('id')
                ->where('username', $username)
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

    public static function getUserIdV2($role){
        $token = session()->get("token_key");
        $accessToken = PersonalAccessToken::findToken($token);

        if ($accessToken) {
            if($accessToken->tokenable){
                Auth::login($accessToken->tokenable);
                $user = Auth::user();
                
                $res = $user->id;
                return $res;
            } else {
                return redirect("/")->with('failed_message','This account is no longer exist');
            }
        } else {
            return null;
        }
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

    public static function getContentOwner($slug_name){
        $query = ContentHeader::select('created_by')
            ->where('slug_name', $slug_name)
            ->limit(1)
            ->get();

        if(count($query) > 0){
            foreach($query as $q){
                $res = $q->created_by;
            }
        } else {
            $res = null;
        }

        return $res;
    }

    public static function getTaskOwner($slug_name){
        $query = Task::select('created_by')
            ->where('slug_name', $slug_name)
            ->limit(1)
            ->get();

        if(count($query) > 0){
            foreach($query as $q){
                $res = $q->created_by;
            }
        } else {
            $res = null;
        }

        return $res;
    }

    public static function getTaskId($slug_name){
        $query = Task::select('id')
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

    public static function getContentDetailId($slug_name){
        $query = ContentDetail::select('contents_details.id as id')
            ->join('contents_headers', 'contents_headers.id', '=', 'contents_details.content_id')
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

    public static function getContentAtt($id){
        $query = ContentDetail::select('content_attach')
            ->where('id', $id)
            ->limit(1)
            ->get();

        if(count($query) > 0){
            foreach($query as $q){
                $res = $q->content_attach;
            }
        } else {
            $res = null;
        }

        return $res;
    }

    public static function getGreeting($datetime){
        $hour = date('H', $datetime);

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

    public static function getListAboutSection(){
        $res = ["about us","helps editor","contact us"];
        
        return $res;
    }

    public static function getListCalendarSection(){
        $res = ["calendar","finished"];
        
        return $res;
    }

    public static function getListFeedbackSection(){
        $res = ["list","most suggest"];
        
        return $res;
    }

    public static function getListFAQSection(){
        $res = ["question","answer"];
        
        return $res;
    }

    public static function getRandomYear(){
        $now = (int)date("Y");
        $res = $now + mt_rand(-3, 6); 
        
        return $res;
    }

    public static function getRandomRole(){
        $total = mt_rand(0, 16); 

        if($total != 0){
            $tag = Tag::inRandomOrder()->take($total)->get();

            foreach($tag as $tg){
                $res[] = (object)[
                    'slug_name' => $tg->slug_name,
                    'tag_name' => $tg->tag_name,
                ];
            }
        } else {
            $res = null;
        }
        
        return $res;
    }

    public static function getRandomDate($null){
        if($null == 0){
            $start = strtotime('2018-01-01 00:00:00');
            $end = strtotime(date("Y-m-d H:i:s"));
            $random = mt_rand($start, $end); 
            $res = date('Y-m-d H:i:s', $random);
        } else {
            $res = null;
        }

        return $res;
    }

    public static function getRandomAdmin($null){
        if($null == 0){
            $admin = Admin::inRandomOrder()->take(1)->get();

            foreach($admin as $ad){
                $res = $ad->id;
            }
        } else {
            $res = null;
        }
        
        return $res;
    }

    public static function getRandomUser($null){
        if($null == 0){
            $user = User::inRandomOrder()->take(1)->get();

            foreach($user as $us){
                $res = $us->id;
            }
        } else {
            $res = null;
        }
        
        return $res;
    }

    public static function getRandomUserArchive($role){
        if($role == 1){
            $user = Archive::select('archives.id', 'archives.created_by')
                ->join('users','users.id','=','archives.created_by')
                ->where('content_title', 'LIKE', '%"slug_name":"lecturer"%')
                ->inRandomOrder()->take(1)->get();
        } else if($role == 2){
            $user = Archive::select('archives.id', 'archives.created_by')
                ->join('users','users.id','=','archives.created_by')
                ->where('content_title', 'LIKE', '%"slug_name":"student"%')
                ->inRandomOrder()->take(1)->get();
        }

        foreach($user as $us){
            $res = [
                'id' => $us->id,
                'user_id' => $us->created_by,
            ];
        }

        return $res;
    }

    public static function getRandomContent($role){
        if($role == 1){
            $role = "lecturer";
        } else if($role == 2){
            $role = "student";
        }
        $content = ContentDetail::select('content_id')
            ->where('content_tag', 'LIKE', '%"slug_name":"'.$role.'"%')
            ->inRandomOrder()->take(1)->get();

        foreach($content as $ct){
            $res = $ct->content_id;
        }

        return $res;
    }

    public static function getRandomDictionaryType($type){
        $dictionary = Dictionary::where('dct_type', $type)->inRandomOrder()->take(1)->get();

        foreach($dictionary as $dct){
            $res = $dct->slug_name;
        }
        
        return $res;
    }

    public static function getProfileImageContent($admin_uname, $user_uname, $admin_img, $user_img){
        // For admin and user (lecturer & staff)
        $res = "";
        if($admin_uname){
            if($admin_img){
                $res = $admin_img;
            } else {
                $res = "http://127.0.0.1:8000/assets/default_admin.png";
            }
        } else {
            if($user_img){
                $res = $user_img;
            } else {
                $res = "http://127.0.0.1:8000/assets/default_lecturer.png";
            }
        }

        return $res;
    }

    public static function getMyImage($id, $role){
        //This query must directly return at least 10 most used tag
        if($role == 0){
            $res = User::select('image_url')
                ->where('id', $id)
                ->first();
        } else {
            $res = Admin::select('image_url')
                ->where('id', $id)
                ->first();
        }

        if(!$res){
            return null;
        } else {
            return $res->image_url;
        }
    }

    public static function getContactTemplate($cat){
        if($cat == "instagram"){
            return "https://www.instagram.com/";
        } else if($cat == "whatsapp"){
            return "https://wa.me/";
        } else if($cat == "twitter"){
            return "https://www.twitter.com/";
        } else {
            return "";
        }
    }

    public static function isMobileDevice() {
        $user = $_SERVER['HTTP_USER_AGENT'];
    
        $type = ['mobile', 'android', 'iphone', 'ipod', 'blackberry', 'windows phone'];
        
        foreach ($type as $key) {
            if (stripos($user, $key) !== false) {
                return true;
            }
        }
    
        return false;
    }

    public static function getUserImage($img1, $img2, $user1){
        if($img1 || $img2){
            if($img1){
                return $img1;
            } else {
                return $img2;
            }
        } else {
            if($user1){
                return 'http://127.0.0.1:8000/assets/default_admin.png';
            } else {
                return 'http://127.0.0.1:8000/assets/default_lecturer.png';
            }
        }
    }

    public static function generateUUIDStorageURL($root,$url){
        $pattern = '/'.$root.'%2F([\w-]+)\?/';
        preg_match($pattern, $url, $matches);
        
        if (isset($matches[1])) {
            return $matches[1];
        }
        return null;
    }

    public static function getTokenResetPass(){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $res = '';
        
        $charCount = strlen($characters);
        for ($i = 0; $i < 6; $i++) {
            $res .= $characters[rand(0, $charCount - 1)];
        }
        
        return $res;
    }
    
    public static function getDateDiffSec($date){
        $ds = $date;
        $de = date("Y-m-d H:i:s");

        $dt1 = new DateTime($ds);
        $dt2 = new DateTime($de);

        $diff = $dt1->diff($dt2);
        $res = $diff->days * 24 * 60 * 60 + $diff->h * 60 * 60 + $diff->i * 60 + $diff->s;

        return $res;  
    }
}
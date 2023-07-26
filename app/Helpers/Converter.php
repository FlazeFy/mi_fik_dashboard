<?php
namespace App\Helpers;

class Converter
{
    public static function getTag($tag_raw){
        if($tag_raw != null){
            //Initial variable
            $tag = [];
            $total_tag = count($tag_raw);

            //Iterate all selected tag
            for($i=0; $i < $total_tag; $i++){
                array_push($tag, $tag_raw[$i]);
            }

            //Clean the json from quotes mark
            $tag = str_replace('"{',"{", json_encode($tag, true));
            $tag = str_replace('}"',"}", $tag);
            $tag = stripslashes($tag);
        } else {
            $tag = null;
        }

        return $tag;
    }

    public static function getFullDate($date, $time){
        if($date && $time){
            return date("Y-m-d H:i", strtotime($date."".$time));
        } else {
            return null;
        }
    }

    public static function getMsgTrashPerContext($total, $name){
        if($total != 0){
            return $total." ".$name.", ";
        } else {
            return "";
        }
    }

    public static function getCleanQuotes($val){
        $val = str_replace('"', "",$val);
        $val = str_replace("'", "",$val);

        return trim($val);
    }
}
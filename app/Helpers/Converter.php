<?php
namespace App\Helpers;

class Converter
{
    // Convert selected tag in JS format to json format.
    // In : [\"{"slug_name":"if_lab", "tag_name":"IF-Lab"}"\]
    // Out : [{"slug_name":"if_lab", "tag_name":"IF-Lab"}]
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

    //Combine date and time
    // In-1 : 2022-03-01  
    // In-2 : 01:30  
    // Out : 2022-03-01 01:30
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
}
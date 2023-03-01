<?php
namespace App\Helpers;

class Generator
{
    //Get slug name from content title, username, etc.
    function getSlugName($val){
        $check = ContentHeader::select('slug_name')
            ->limit(1)
            ->get();

        if(count($check) > 0){
            $val = $val."_".date('mdhis'); 
        }

        $replace = str_replace("/","", stripslashes($val));
        $replace = str_replace(" ","_", $replace);
        $replace = str_replace("-","_", $replace);

        return strtolower($replace);
    }
}
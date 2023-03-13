<?php
namespace App\Helpers;

class Query
{
    public static function getSelectTemplate($type){ 
        if($type == "content_thumbnail"){
            $query = "slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,
                content_date_end,content_tag,contents_headers.created_at,
                count(contents_viewers.id) as total_views";

        } else if($type == "content_schedule"){
            $query = "slug_name,content_title,content_desc, 
                content_loc,content_tag, content_date_start, content_date_end, 
                1 as data_from";

        } else if($type == "content_detail"){
            $query = "slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,content_date_end,
                content_tag,content_attach,contents_headers.created_at,
                count(contents_viewers.id) as total_views";
                
        } else if($type == "task_schedule"){
            $query = "slug_name, task_title as content_title, 
                task_desc as content_desc, null as content_loc, null as content_tag, 
                task_date_start as content_date_start, task_date_end as content_date_end, 
                2 as data_from";
        } 

        return $query;
    }
}
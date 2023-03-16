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
        } else if($type == "user_request_new"){
            $query = "slug_name, username, CONCAT(first_name,' ',last_name) as full_name, 
                role, created_at, accepted_at, is_accepted";
        } else if($type == "user_request_old"){
            $query = "users_requests.id, slug_name, username, CONCAT(first_name,' ',last_name) 
                as full_name, tag_slug_name, request_type, users_requests.created_at, created_by";
        } else if($type == "user_detail"){
            $query = "slug_name, username, email, password, CONCAT(first_name,' ',last_name) as full_name, role, image_url, 
                created_at, updated_at, updated_by, deleted_at, deleted_by, accepted_at, accepted_by, is_accepted";
        }
        // Make user's new request dump query
        // Make user's old request dump query

        return $query;
    }
}
<?php
namespace App\Helpers;

class Query
{
    public static function getSelectTemplate($type){ 
        if($type == "content_thumbnail"){
            $query = "slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,
                content_date_end,content_tag,contents_headers.created_at,
                admins.username as admin_username_created, users.username as user_username_created, 
                admins.image_url as admin_image_created, users.image_url as user_image_created, 
                count(contents_viewers.id) as total_views";
        } else if($type == "content_draft_homepage"){
            $query = "slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,
                content_date_end,content_tag,contents_headers.created_at,
                admins.username as admin_username_created, users.username as user_username_created, 
                admins.image_url as admin_image_created, users.image_url as user_image_created";
        } else if($type == "content_schedule"){
            $query = "contents_headers.id, slug_name,content_title,content_desc, 
                content_loc,content_tag, content_date_start, content_date_end, 
                1 as data_from";

        } else if($type == "content_detail"){
            $query = "ch.slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,content_date_end,
                content_tag,content_attach,ch.created_at,ch.updated_at,is_draft,
                ac.username as admin_username_created, uc.username as user_username_created, 
                au.username as admin_username_updated, uu.username as user_username_updated, 
                ad.username as admin_username_deleted, ud.username as user_username_deleted,
                count(cv.id) as total_views";
        
        } else if($type == "content_location"){
            $query = "content_id, slug_name, content_title, content_desc, content_date_start, 
                content_date_end, content_loc";
                
        } else if($type == "task_schedule"){
            $query = "tasks.id, slug_name, task_title as content_title, 
                task_desc as content_desc, null as content_loc, null as content_tag, 
                task_date_start as content_date_start, task_date_end as content_date_end, 
                2 as data_from";

        } else if($type == "user_request_new"){
            $query = "username, CONCAT(first_name,' ',last_name) as full_name, 
                role, created_at, accepted_at, is_accepted";
                
        } else if($type == "user_request_old"){

            $query = "users_requests.id, username, CONCAT(first_name,' ',last_name) 
                as full_name, tag_slug_name, request_type, users_requests.created_at, created_by";

        } else if($type == "user_detail"){
            $query = "username, email, password, CONCAT(first_name,' ',last_name) as full_name, role, image_url, 
                CASE 
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."lecturer".'"'."%' THEN 'Lecturer'
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."staff".'"'."%' THEN 'Staff' 
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."student".'"'."%' THEN 'Student'
                END AS general_role,
                created_at, updated_at, updated_by, deleted_at, deleted_by, accepted_at, accepted_by, is_accepted";

        } else if($type == "group_detail"){
            $query = "users_groups.id, slug_name, group_name, group_desc, count(groups_relations.id) as total, users_groups.created_at, users_groups.created_by, updated_at, updated_by";
        } else if($type == "viewed_event_role"){ 
            $query = "contents_headers.id as id_content, content_title, COUNT(1) as total,
                COUNT(CASE WHEN users.role LIKE '%".'"'."slug_name".'"'.":".'"'."lecturer".'"'."%' OR users.role LIKE '%".'"'."slug_name".'"'.":".'"'."staff".'"'."%' THEN 1 END) AS total_lecturer,
                COUNT(CASE WHEN users.role NOT LIKE '%".'"'."slug_name".'"'.":".'"'."lecturer".'"'."%' AND users.role NOT LIKE '%".'"'."slug_name".'"'.":".'"'."staff".'"'."%' THEN 1 END) AS total_student";
        } else if($type == "notif_my"){
            $query = "notifications.id, notif_type, notif_body, notif_send_to, is_pending, notifications.created_at, CONCAT(users.first_name, ' ', users.last_name) as users_fullname, 
                CONCAT(admins.first_name, ' ', admins.last_name) as admins_fullname";
        } else if($type == "event_dump"){
            $query = "ch.slug_name, content_title, content_desc, 
                ac.username as admin_username_created, uc.username as user_username_created, 
                ac.image_url as admin_image_created, uc.image_url as user_image_created,
                ad.image_url as admin_image_deleted, ud.image_url as user_image_deleted,  
                au.username as admin_username_updated, uu.username as user_username_updated, 
                ad.username as admin_username_deleted, ud.username as user_username_deleted,
                content_loc, content_tag, content_date_start, content_date_end, ch.created_at,
                1 as data_from, ch.deleted_at as deleted_at";
        } else if($type == "task_dump"){
            $query = "ts.slug_name, task_title as content_title, task_desc as content_desc, 
                null as admin_username_created, uc.username as user_username_created, 
                null as admin_image_created, uc.image_url as user_image_created, 
                null as admin_image_deleted, ud.image_url as user_image_deleted,
                null as admin_username_updated, uu.username as user_username_updated, 
                null as admin_username_deleted, ud.username as user_username_deleted,
                null as content_loc, null as content_tag, task_date_start as content_date_start, task_date_end as content_date_end, ts.created_at,
                2 as data_from, ts.deleted_at as deleted_at";
        }
        // Make user's new request dump query
        // Make user's old request dump query

        return $query;
    }

    public static function getJoinTemplate($type, $initial){
        if($type == "content_dump"){
            return "LEFT JOIN admins ac ON ".$initial.".created_by = ac.id
                LEFT JOIN users uc ON ".$initial.".created_by = uc.id
                LEFT JOIN admins au ON ".$initial.".updated_by = au.id
                LEFT JOIN users uu ON ".$initial.".updated_by = uu.id
                LEFT JOIN admins ad ON ".$initial.".deleted_by = ad.id
                LEFT JOIN users ud ON ".$initial.".deleted_by = ud.id";    
        } else if($type == "content_detail"){
            return "LEFT JOIN admins ac ON ".$initial.".created_by = ac.id
                LEFT JOIN users uc ON ".$initial.".created_by = uc.id
                LEFT JOIN admins au ON ".$initial.".updated_by = au.id
                LEFT JOIN users uu ON ".$initial.".updated_by = uu.id
                LEFT JOIN admins ad ON ".$initial.".deleted_by = ad.id
                LEFT JOIN users ud ON ".$initial.".deleted_by = ud.id
                LEFT JOIN contents_viewers cv ON cv.content_id = ch.id";    
        }
    }

    public static function getWhereDateTemplate($date_start, $date_end){
        if($date_start == $date_end){
            $query = "
                content_date_start >= '".$date_start."' and content_date_end <= '".$date_end."'
            "; //Check this shit
        } else {
            $query = "
                (content_date_start <= '".$date_start."' and content_date_end >= '".$date_start."')
                OR
                (content_date_start <= '".$date_end."' and content_date_end >= '".$date_end."')
                OR
                (content_date_start >= '".$date_start."' and content_date_end <= '".$date_end."')
            ";
        }
        
        return $query;
    }
}
<?php
namespace App\Helpers;
use App\Models\PersonalAccessTokens;
use App\Models\User;

class Query
{
    public static function getSelectTemplate($type){ 
        if($type == "content_thumbnail"){
            $query = "slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,
                content_date_end,content_tag,
                admins.username as admin_username_created, users.username as user_username_created, 
                admins.image_url as admin_image_created, users.image_url as user_image_created, 
                count(contents_viewers.id) as total_views";
        } else if($type == "content_properties"){
            $query = "admins.username as admin_username_created, users.username as user_username_created, 
                admins.image_url as admin_image_created, users.image_url as user_image_created, 
                count(contents_viewers.id) as total_views";
        } else if($type == "content_properties_null"){
            $query = "null as admin_username_created, null as user_username_created, 
                null as admin_image_created, null as user_image_created, 
                0 as total_views";
        } else if($type == "content_draft_homepage"){
            $query = "slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,
                content_date_end,content_tag,contents_headers.created_at,
                admins.username as admin_username_created, users.username as user_username_created, 
                admins.image_url as admin_image_created, users.image_url as user_image_created";
        } else if($type == "content_schedule"){
            $query = "contents_headers.id, contents_headers.slug_name, content_title,content_desc, 
                content_loc,content_tag, content_date_start, content_date_end, 
                1 as data_from";

        } else if($type == "content_detail"){
            $query = "ch.slug_name,content_title,content_desc,
                content_loc,content_image,content_date_start,content_date_end,
                content_tag,content_attach,ch.created_at,ch.updated_at,is_draft,
                ac.username as admin_username_created, uc.username as user_username_created, 
                ac.image_url as admin_image_created, uc.image_url as user_image_created, 
                au.username as admin_username_updated, uu.username as user_username_updated, 
                ad.username as admin_username_deleted, ud.username as user_username_deleted,
                count(cv.id) as total_views";
        
        } else if($type == "content_location"){
            $query = "content_id, slug_name, content_title, content_desc, content_date_start, 
                content_date_end, content_loc";
                
        } else if($type == "task_schedule"){
            $query = "tasks.id, tasks.slug_name, task_title as content_title, 
                task_desc as content_desc, null as content_loc, null as content_tag, 
                task_date_start as content_date_start, task_date_end as content_date_end, 
                2 as data_from";

        } else if($type == "user_request_new"){
            $query = "username, CONCAT(first_name,' ',COALESCE(last_name, '')) as full_name, 
                role, created_at, accepted_at, is_accepted, image_url";
                
        } else if($type == "user_request_old"){

            $query = "users_requests.id, username, CONCAT(first_name,' ',COALESCE(last_name, '')) as full_name, 
                users.accepted_at, tag_slug_name, request_type, users_requests.created_at, created_by, image_url, role";

        } else if($type == "user_detail"){
            $query = "username, email, password, CONCAT(first_name,' ',COALESCE(last_name, '')) as full_name, role, image_url, 
                CASE 
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."lecturer".'"'."%' THEN 'Lecturer'
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."staff".'"'."%' THEN 'Staff' 
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."student".'"'."%' THEN 'Student'
                END AS general_role,
                created_at, updated_at, updated_by, deleted_at, deleted_by, accepted_at, accepted_by, is_accepted";
        } else if($type == "access_info"){
            $query = "personal_access_tokens.id, SUBSTR(tokenable_type, 12) as type, token, last_used_at, expires_at, personal_access_tokens.created_at, personal_access_tokens.updated_at,
                admins.username as admin_username, users.username as user_username, CONCAT(admins.first_name,' ',COALESCE(admins.last_name, '')) as admin_fullname, 
                CONCAT(users.first_name,' ',COALESCE(users.last_name, '')) as user_fullname";
        } else if($type == "group_detail"){
            $query = "users_groups.id, slug_name, group_name, group_desc, count(groups_relations.id) as total, users_groups.created_at, users_groups.created_by, updated_at, updated_by";
        } else if($type == "group_relation"){
            $query = "groups_relations.id, username, CONCAT(first_name,' ',COALESCE(last_name, '')) as full_name, 
                CASE 
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."lecturer".'"'."%' THEN 'Lecturer'
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."staff".'"'."%' THEN 'Staff' 
                    WHEN role LIKE '%".'"'."slug_name".'"'.":".'"'."student".'"'."%' THEN 'Student'
                END AS general_role,
                image_url, email, users.accepted_at as joined_at, groups_relations.created_at as added_at";
        } else if($type == "viewed_event_role"){ 
            $query = "contents_headers.id as id_content, content_title, COUNT(1) as total,
                COUNT(CASE WHEN users.role LIKE '%".'"'."slug_name".'"'.":".'"'."lecturer".'"'."%' OR users.role LIKE '%".'"'."slug_name".'"'.":".'"'."staff".'"'."%' THEN 1 END) AS total_lecturer,
                COUNT(CASE WHEN users.role NOT LIKE '%".'"'."slug_name".'"'.":".'"'."lecturer".'"'."%' AND users.role NOT LIKE '%".'"'."slug_name".'"'.":".'"'."staff".'"'."%' THEN 1 END) AS total_student";
        } else if($type == "notif_my"){
            $query = "notifications.id, CONCAT(UPPER(SUBSTR(notif_type, 14)),' ',notif_body) as notif_body, notif_title, notifications.created_at, CONCAT(admins.first_name, ' ', COALESCE(admins.last_name, '')) as admin_fullname";
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
        } else if($type == "tag_dump"){
            $query = "tg.slug_name, tag_name as content_title, tag_desc as content_desc, 
                ac.username as admin_username_created, null as user_username_created, 
                ac.image_url as admin_image_created, null as user_image_created, 
                ad.image_url as admin_image_deleted, null as user_image_deleted,
                au.username as admin_username_updated, null as user_username_updated, 
                ad.username as admin_username_deleted, null as user_username_deleted,
                null as content_loc, dct_name as content_tag, null as content_date_start, null as content_date_end, tg.created_at,
                3 as data_from, tg.deleted_at as deleted_at";
        } else if($type == "info_dump"){
            $query = "inf.id, info_type as content_title, info_body as content_desc, 
                ac.username as admin_username_created, null as user_username_created, 
                ac.image_url as admin_image_created, null as user_image_created, 
                ad.image_url as admin_image_deleted, null as user_image_deleted,
                au.username as admin_username_updated, null as user_username_updated, 
                ad.username as admin_username_deleted, null as user_username_deleted,
                null as content_loc, CONCAT(info_page,'/',info_location) as content_tag, null as content_date_start, null as content_date_end, inf.created_at,
                5 as data_from, inf.deleted_at as deleted_at";
        } else if($type == "question_dump"){
            $query = "qt.id, question_type as content_title, question_body as content_desc, 
                null as admin_username_created, null as user_username_created, 
                null as admin_image_created, null as user_image_created, 
                null as admin_image_deleted, null as user_image_deleted,
                null as admin_username_updated, null as user_username_updated, 
                null as admin_username_deleted, null as user_username_deleted,
                null as content_loc, question_answer as content_tag, null as content_date_start, null as content_date_end, qt.created_at as created_at,
                8 as data_from, qt.deleted_at as deleted_at";
        } else if($type == "feedback_dump"){
            $query = "fb.id, feedback_rate as content_title, feedback_body as content_desc, 
                null as admin_username_created, null as user_username_created, 
                null as admin_image_created, null as user_image_created, 
                null as admin_image_deleted, null as user_image_deleted,
                null as admin_username_updated, null as user_username_updated, 
                null as admin_username_deleted, null as user_username_deleted,
                null as content_loc, SUBSTR(feedback_suggest,10) as content_tag, null as content_date_start, null as content_date_end, fb.created_at as created_at,
                6 as data_from, fb.deleted_at as deleted_at";
        } else if($type == "dictionary_dump"){
            $query = "dct.id, dct_name as content_title, dct_desc as content_desc, 
                null as admin_username_created, null as user_username_created, 
                null as admin_image_created, null as user_image_created, 
                null as admin_image_deleted, null as user_image_deleted,
                null as admin_username_updated, null as user_username_updated, 
                null as admin_username_deleted, null as user_username_deleted,
                null as content_loc, dct.slug_name as content_tag, null as content_date_start, null as content_date_end, dct.created_at as created_at,
                7 as data_from, dct.deleted_at as deleted_at";
        } else if($type == "group_dump"){
            $query = "ug.slug_name, group_name as content_title, group_desc as content_desc, 
                ac.username as admin_username_created, null as user_username_created, 
                ac.image_url as admin_image_created, null as user_image_created, 
                ad.image_url as admin_image_deleted, null as user_image_deleted,
                au.username as admin_username_updated, null as user_username_updated, 
                ad.username as admin_username_deleted, null as user_username_deleted,
                null as content_loc, CONCAT(count(gr.id), ' member') as content_tag, null as content_date_start, null as content_date_end, ug.created_at,
                4 as data_from, ug.deleted_at as deleted_at";
        } else if($type == "dictionary_manage"){
            $query = "dc.id,slug_name, dct_name, dct_desc, dct_type, dc.created_at, dc.updated_at, dc.deleted_at,
                ac.username as admin_username_created, ac.image_url as admin_image_created, 
                ad.image_url as admin_image_deleted, ad.username as admin_username_deleted,
                au.username as admin_username_updated, au.image_url as admin_image_updated";
        } else if($type == "info_manage"){
            $query = "inf.id,info_type, info_page, info_location, info_body, is_active, inf.created_at, inf.updated_at, inf.deleted_at,
                ac.username as admin_username_created, ac.image_url as admin_image_created, 
                ad.image_url as admin_image_deleted, ad.username as admin_username_deleted,
                au.username as admin_username_updated, au.image_url as admin_image_updated";
        } else if($type == "notif_manage"){
            $query = "nt.id, notif_type, notif_title, notif_body, notif_send_to, is_pending, pending_until, nt.created_at, sended_at, nt.updated_at, nt.deleted_at,
                ac.username as admin_username_created, ac.image_url as admin_image_created, 
                ad.image_url as admin_image_deleted, ad.username as admin_username_deleted,
                au.username as admin_username_updated, au.image_url as admin_image_updated,
                asd.username as admin_username_sended, asd.image_url as admin_image_sended";
        }

        return $query;
    }

    public static function getJoinTemplate($type, $initial){
        if($type == "content_dump"){
            $query = "LEFT JOIN admins ac ON ".$initial.".created_by = ac.id
                LEFT JOIN users uc ON ".$initial.".created_by = uc.id
                LEFT JOIN admins au ON ".$initial.".updated_by = au.id
                LEFT JOIN users uu ON ".$initial.".updated_by = uu.id
                LEFT JOIN admins ad ON ".$initial.".deleted_by = ad.id
                LEFT JOIN users ud ON ".$initial.".deleted_by = ud.id";    
        } else if($type == "content_detail"){
            $query = "LEFT JOIN admins ac ON ".$initial.".created_by = ac.id
                LEFT JOIN users uc ON ".$initial.".created_by = uc.id
                LEFT JOIN admins au ON ".$initial.".updated_by = au.id
                LEFT JOIN users uu ON ".$initial.".updated_by = uu.id
                LEFT JOIN admins ad ON ".$initial.".deleted_by = ad.id
                LEFT JOIN users ud ON ".$initial.".deleted_by = ud.id
                LEFT JOIN contents_viewers cv ON cv.content_id = ch.id";    
        } else if($type == "tag"){
            $query = "LEFT JOIN admins ac ON ".$initial.".created_by = ac.id
                LEFT JOIN admins au ON ".$initial.".updated_by = au.id
                LEFT JOIN admins ad ON ".$initial.".deleted_by = ad.id";
        } else if($type == "notif"){
            $query = "LEFT JOIN admins ac ON ".$initial.".created_by = ac.id
                LEFT JOIN admins au ON ".$initial.".updated_by = au.id
                LEFT JOIN admins ad ON ".$initial.".deleted_by = ad.id
                LEFT JOIN admins asd ON ".$initial.".sended_by = asd.id";
        }
        return $query;
    }

    public static function getWhereDateTemplate($date_start, $date_end, $offset){
        if($date_start == $date_end){
            $query = "
                DATE_FORMAT(DATE_ADD(content_date_start, INTERVAL ".$offset." HOUR), '%Y-%m-%d') <= '".$date_start."'
            ";
        } else {
            $query = "
                ((DATE_FORMAT(DATE_ADD(content_date_start, INTERVAL ".$offset." HOUR), '%Y-%m-%d') <= '".$date_start."' 
                    and DATE_FORMAT(DATE_ADD(content_date_end, INTERVAL ".$offset." HOUR), '%Y-%m-%d') >= '".$date_start."')
                OR
                (DATE_FORMAT(DATE_ADD(content_date_start, INTERVAL ".$offset." HOUR), '%Y-%m-%d') <= '".$date_end."' 
                    and DATE_FORMAT(DATE_ADD(content_date_end, INTERVAL ".$offset." HOUR), '%Y-%m-%d') >= '".$date_end."')
                OR
                (DATE_FORMAT(DATE_ADD(content_date_start, INTERVAL ".$offset." HOUR), '%Y-%m-%d') >= '".$date_start."' 
                    and DATE_FORMAT(DATE_ADD(content_date_end, INTERVAL ".$offset." HOUR), '%Y-%m-%d') <= '".$date_end."'))
            ";
        }
        
        return $query;
    }

    public static function getAccessRole($user_id, $is_general){
        $based_role = null;
        
        $check = PersonalAccessTokens::where('tokenable_id', $user_id)->first();
        if($check->tokenable_type === "App\\Models\\User"){ // User
            $user = User::select('role')->where('id', $user_id)->first();
            $roles = $user->role;

            if($is_general){    
                foreach($roles as $rl){
                    if($rl['slug_name'] == 'student'){
                        $based_role = "student";
                    } else if($rl['slug_name'] == 'lecturer' || $rl['slug_name'] == 'staff'){
                        $based_role = "lecturer";
                    }
                }
            } else {
                $user = User::where('id',$user_id)->first();
                $arr_roles = "";
                $total = count($roles);
                for($i = 0; $i < $total; $i++){
                    $end = "";
                    if($i != $total - 1){
                        $end = "|";
                    } 
                    $arr_roles .= $roles[$i]['slug_name'].$end;
                }
                $based_role = "JSON_EXTRACT(content_tag, '$[*].slug_name') REGEXP '(".$arr_roles.")'";
            }
        } else {
            $based_role = "admin";
        }
        
        return $based_role;
    }
}
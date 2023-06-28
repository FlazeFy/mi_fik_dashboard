<?php
//For subdomain deploy!!!
//namespace App\Http\Controllers\Mifik;
//use App\Http\Controllers\Controller;

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Helpers\Converter;
use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\Tag;
use App\Models\History;
use App\Models\Menu;
use App\Models\Archive;
use App\Models\ArchiveRelation;
use App\Models\ContentViewer;
use App\Models\Task;
use App\Models\Info;
use App\Models\Dictionary;
use App\Models\User;
use App\Models\UserRequest;

use App\Mail\OrganizerEmail;
use Illuminate\Support\Facades\Mail;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = ["Reminder", "Attachment", "Notification"];
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($user_id != null){
            if(!session()->get('selected_tag_calendar')){
                session()->put('selected_tag_calendar', "All");
            }
            if(!session()->get('selected_role_user')){
                session()->put('selected_role_user', "All");
            }
            if(!session()->get('selected_tag_category')){
                session()->put('selected_tag_category', "All");
            }
            if(!session()->get('selected_filter_suggest')){
                session()->put('selected_filter_suggest', "All");
            }
            if(!session()->get('selected_filter_info_type')){
                session()->put('selected_filter_info_type', "All");
            }
            if(!session()->get('selected_view_mve_chart')){
                session()->put('selected_view_mve_chart', "All");
            }
            if(!session()->get('toogle_edit_app')){
                session()->put('toogle_edit_app', 'false');
            }
            if(!session()->get('toogle_edit_help')){
                session()->put('toogle_edit_help', 'false');
            }
            if(!session()->get('toogle_edit_contact')){
                session()->put('toogle_edit_contact', 'false');
            }
            if(!session()->get('ordering_event')){
                session()->put('ordering_event', "DESC");
            }
            if(!session()->get('ordering_finished')){
                session()->put('ordering_finished', "ASC");
            }
            if(!session()->get('ordering_trash')){
                session()->put('ordering_trash', "DESC");
            }
            if(!session()->get('ordering_user_list')){
                session()->put('ordering_user_list', "username__DESC");
            }
            if(!session()->get('ordering_group_list')){
                session()->put('ordering_group_list', "group_name__DESC");
            }
            if(!session()->get('filtering_date')){
                session()->put('filtering_date', "all");
            }
            if(!session()->get('filtering_trash')){
                session()->put('filtering_trash', "all");
            }
            if(!session()->get('filtering_fname')){
                session()->put('filtering_fname', "all");
            }
            if(!session()->get('filtering_lname')){
                session()->put('filtering_lname', "all");
            }

            if(!session()->get('about_menu')){
                $about_menu = Generator::getListAboutSection();
                session()->put('about_menu', $about_menu);
            }
            if(!session()->get('calendar_menu')){
                $calendar_menu = Generator::getListCalendarSection();
                session()->put('calendar_menu', $calendar_menu);
            }
            if(!session()->get('feedback_menu')){
                $feedback_menu = Generator::getListFeedbackSection();
                session()->put('feedback_menu', $feedback_menu);
            }
            if(!session()->get('faq_menu')){
                $faq_menu = Generator::getListFAQSection();
                session()->put('faq_menu', $faq_menu);
            }
            
            $menu = Menu::getMenu();
            $info = Info::getAvailableInfo("homepage");
            $dictionary = Dictionary::getDictionaryByType($type);
            //$archive = Archive::getMyArchive($user_id, "DESC");
            $greet = Generator::getGreeting(date('h'));

            if(Session::has('recatch_message') && $role == 1){
                $count = [
                    'count_request' => UserRequest::where('is_accepted',0)->whereNull('is_rejected')->count(),
                    'count_empty_role' => User::whereNull('role')->whereNotNull('accepted_at')->count(),
                    'count_new' => User::whereNull('accepted_at')->count()
                ];
                $count = json_decode(json_encode($count), false);
                
                session()->put('recatch', true);
            } else {
                $count = null;
            }
            
            if($role == 1){
                $tag = Tag::getFullTag("DESC", "DESC");
                $dct_tag = Dictionary::getDictionaryByType("Tag");
                $mydraft = ContentHeader::getMyDraft($role, $user_id);
                $mytag = null;
            } else {
                $tag = null;
                $dct_tag = null;
                $mydraft = ContentHeader::getMyDraft($role, $user_id);
                $list = User::getUserRole($user_id,$role);

                foreach($list as $l){
                    $mytag = $l->role;
                }
            }
            
            //Set active nav
            session()->put('active_nav', 'homepage');
            session()->forget('active_subnav');

            return view ('homepage.index')
                ->with('tag', $tag)
                ->with('mytag', $mytag)
                ->with('menu', $menu)
                ->with('info', $info)
                ->with('mydraft', $mydraft)
                ->with('dct_tag', $dct_tag)
                ->with('dictionary', $dictionary)
                ->with('count', $count)
                //->with('archive', $archive)
                ->with('greet',$greet);
        } else {
            return redirect("/")->with('failed_message','Session lost, please sign in again');
        }
    }

    // ================================= MVC =================================

    public function add_event(Request $request)
    {
        DB::beginTransaction();

        try{
            //Inital variable
            $draft = 0;
            $failed_attach = false;

            //Helpers
            $validator = Validation::getValidateEvent($request);
            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "event",
                    'history_body' => "has created an event"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    $role = session()->get('role_key');
                    $tag = Converter::getTag($request->content_tag);
                    $fulldate_start = Converter::getFullDate($request->content_date_start, $request->content_time_start);
                    $fulldate_end = Converter::getFullDate($request->content_date_end, $request->content_time_end);
                    $user_id = Generator::getUserIdV2($role);
                    $slug = Generator::getSlugName($request->content_title, "content");
                    $uuid = Generator::getUUID();

                    if($request->content_image || $request->content_image != ""){
                        $imageURL = $request->content_image;
                    } else {
                        $imageURL = null;
                    }

                    $header = [
                        'id' => $uuid,
                        'slug_name' => $slug,
                        'content_title' => $request->content_title,
                        'content_desc' => $request->content_desc,
                        'content_date_start' => $fulldate_start,
                        'content_date_end' => $fulldate_end,
                        'content_reminder' => $request->content_reminder,
                        'content_image' => $imageURL,
                        'is_draft' => $draft,
                        'created_at' => date("Y-m-d H:i"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'updated_by' => null,
                        'deleted_at' => null,
                        'deleted_by' => null
                    ];

                    DB::table("contents_headers")->insert($header);

                    if($tag || $request->has('content_attach')){
                        $detail = [
                            'id' => Generator::getUUID(),
                            'content_id' => $uuid, 
                            'content_attach' => $request->content_attach,
                            'content_tag' => $tag,
                            'content_loc' => $request->content_loc,
                            'created_at' => date("Y-m-d H:i"),
                            'updated_at' => null
                        ];

                        DB::table("contents_details")->insert($detail);
                    }

                    DB::table("histories")->insert([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type,
                        'context_id' => $uuid,
                        'history_body' => $data->history_body,
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);

                    if($role != 1){
                        $notif_body = "You has been created an event called '".$request->content_title."'";
                        $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
                        $messaging = $factory->createMessaging();

                        $users = DB::table("users")->select("username", "firebase_fcm_token")
                            ->where("id",$user_id)
                            ->first();

                        if($users){
                            $notif_body = "You has been created an event called '".$request->content_title."'";
                            $firebase_token = $users->firebase_fcm_token;
                            if($firebase_token){
                                $validateRegister = $messaging->validateRegistrationTokens($firebase_token);
        
                                if($validateRegister['valid'] != null){
                                    $notif_title = "Hello ".$users->username.", you got an information";
                                    $message = CloudMessage::withTarget('token', $firebase_token)
                                        ->withNotification(
                                            FireNotif::create($notif_body)
                                            ->withTitle($notif_title)
                                            ->withBody(strtoupper($data->history_type)." ".$notif_body)
                                        )
                                        ->withData([
                                            'by' => 'person'
                                        ]);
                                    $response = $messaging->send($message);
                                } else {
                                    DB::table("users")->where('id', $user_id)->update([
                                        "firebase_fcm_token" => null
                                    ]);
                                }
                            }
                        }
                    }

                    DB::commit();

                    if(session()->get('email_key')){
                        Mail::to(session()->get('email_key'))->send(new OrganizerEmail($header, $detail));
                    }

                    return redirect()->back()->with('success_message', 'Create content success');
                }
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Create content failed '.$e);
        }
    }

    public function add_task(Request $request){
        $slug = Generator::getSlugName($request->task_title, "task");

        $fulldate_start = Converter::getFullDate($request->task_date_start, $request->task_time_start);
        $fulldate_end = Converter::getFullDate($request->task_date_end, $request->task_time_end);
        $user_id = Generator::getUserIdV2(session()->get('role_key'));
        $uuid = Generator::getUUID();

        $header = Task::create([
            'id' => $uuid,
            'slug_name' => $slug,
            'task_title' => $request->task_title,
            'task_desc' => $request->task_desc,
            'task_date_start' => $fulldate_start,
            'task_date_end' => $fulldate_end,
            'task_reminder' => $request->task_reminder,

            'created_at' => date("Y-m-d H:i"),
            'created_by' => $user_id, //for now
            'updated_at' => null,
            'updated_by' => null,
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        if(is_countable($request->archive_rel)){
            $ar_count = count($request->archive_rel);

            for($i = 0; $i < $ar_count; $i++){
                if($request->has('archive_rel.'.$i)){
                    ArchiveRelation::create([
                        'id' => Generator::getUUID(),
                        'archive_id' => $request->archive_rel[$i],
                        'content_id' => $uuid,
                        'created_at' => date("Y-m-d H:i"),
                        'created_by' => $user_id
                    ]);
                }
            }
        }

        return redirect()->back()->with('success_message', 'Create item success');
    }

    public function add_content_task_relation(Request $request, $slug_name, $type){
        if($type == 0){
            $content = ContentHeader::select('id')
                ->where('slug_name', $slug_name)
                ->get();

            if(count($content) > 0){
                $id = $content['id'][0];
                $user_id = Generator::getUserIdV2(session()->get('role_key'));

                ArchiveRelation::create([
                    'id' => Generator::getUUID(),
                    'archive_id' => $request->archive_id,
                    'content_id' => $id,
                    'created_at' => date("Y-m-d H:i"),
                    'created_by' =>  $user_id
                ]);

                return redirect()->back()->with('success_message', 'Update item success');
            } else {
                return redirect()->back()->with('failed_message', 'Update item failed');
            }
        } else {
            ArchiveRelation::destroy($slug_name);

            return redirect()->back()->with('success_message', 'Create item success');
        }
    }

    public function add_content_view($slug_name){
        $content_id = Generator::getContentId($slug_name);
        $user_id = Generator::getUserIdV2(session()->get('role_key'));
        $viewer = ContentViewer::getViewByContentIdUserId($content_id, $user_id);

        if($viewer){
            ContentViewer::where('id', $viewer)->update([
                'created_at' => date("Y-m-d H:i:s")
            ]);
        } else {
            ContentViewer::create([
                'id' => Generator::getUUID(),
                'content_id' => $content_id,
                'type_viewer' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            ]);
        }

        return redirect('event/detail/'.$slug_name);
    }

    public function set_ordering_content($order)
    {
        session()->put('ordering_event', $order);

        return redirect()->back()->with('success_message', 'Content ordered');
    }

    public function set_filter_date(Request $request)
    {
        session()->put('filtering_date', $request->date_start."_".$request->date_end);

        return redirect()->back()->with('success_message', 'Content filtered');
    }

    public function reset_filter_date()
    {
        session()->put('filtering_date', 'all');

        return redirect()->back()->with('success_message', 'Content filtered');
    }
}

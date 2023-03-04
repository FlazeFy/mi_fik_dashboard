<?php
//For subdomain deploy!!!
//namespace App\Http\Controllers\Mifik;
//use App\Http\Controllers\Controller;

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Helpers\Converter;
use App\Helpers\Generator;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\Tag;
use App\Models\Archive;
use App\Models\ArchiveRelation;
use App\Models\Task;
use App\Models\Setting;
use App\Models\Dictionary;
use App\Models\Notification;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = 'dc4d52ec-afb1-11ed-afa1-0242ac120002'; //for now.
        $type = ["Reminder", "Attachment"];
        
        if(!session()->get('selected_tag_calendar')){
            session()->put('selected_tag_calendar', "All");
        }

        $tag = Tag::getFullTag("DESC", "DESC");
        $dictionary = Dictionary::getDictionaryByType($type);
        $archive = Archive::getMyArchive($user_id, "DESC");

        //Set active nav
        session()->put('active_nav', 'homepage');

        return view ('homepage.index')
            ->with('tag', $tag)
            ->with('dictionary', $dictionary)
            ->with('archive', $archive);
    }

    // ================================= MVC =================================

    public function add_event(Request $request)
    {
        //Inital variable 
        $draft = 0;
        $failed_attach = false;

        //Helpers
        $tag = Converter::getTag($request->content_tag);
        $fulldate_start = Converter::getFullDate($request->content_date_start, $request->content_time_start);
        $fulldate_end = Converter::getFullDate($request->content_date_end, $request->content_time_end);
        $slug = Generator::getSlugName($request->content_title, "content");

        // Attachment file upload
        $status = true;

        if(is_countable($request->attach_input)){
            $att_count = count($request->attach_input);
        
            for($i = 0; $i < $att_count; $i++){
                if($request->hasFile('attach_input.'.$i)){
                    //validate image
                    $this->validate($request, [
                        'attach_input.'.$i     => 'required|max:10000',
                    ]);
        
                    //upload image
                    $att_file = $request->file('attach_input.'.$i);
                    $att_file->storeAs('public', $att_file->getClientOriginalName());

                    //get success message 
                    // ????
                    $status = true;
                } else {
                    $status = false;
                }
            }
        } else {
            $status = true;
        }

        // Content image file upload
        if($request->hasFile('content_image')){
            //validate image
            $this->validate($request, [
                'content_image'    => 'required|max:5000',
            ]);

            //upload image
            $att_file = $request->file('content_image');
            $imageURL = $att_file->hashName();
            $att_file->storeAs('public', $imageURL);
        } else {
            $imageURL = null;
        }
    
        if(!$status){
            $draft = 1;
            $failed_attach = true;
        }

        $header = ContentHeader::create([
            'slug_name' => $slug, 
            'content_title' => $request->content_title,
            'content_desc' => $request->content_desc,
            'content_date_start' => $fulldate_start,
            'content_date_end' => $fulldate_end,
            'content_reminder' => $request->content_reminder,
            'content_image' => $imageURL,
            'is_draft' => $draft, 
            'created_at' => date("Y-m-d H:i"),
            'created_by' => 1, //for now
            'updated_at' => null,
            'updated_by' => null,
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        if($tag || $request->has('content_attach')){
            function getFailedAttach($failed, $att_content){
                if($failed){
                    return null;
                } else {
                    return $att_content;
                }
            }
            
            ContentDetail::create([
                'content_id' => $header->id, //for now
                'content_attach' => getFailedAttach($failed_attach, $request->content_attach), 
                'content_tag' => $tag,
                'content_loc' => null, //for now 
                'created_by' => date("Y-m-d H:i"), 
                'updated_at' => null
            ]);
        }

        return redirect()->back()->with('success_message', 'Create content success');
    }
    
    public function add_task(Request $request){
        $slug = Generator::getSlugName($request->task_title, "task");

        $fulldate_start = Converter::getFullDate($request->task_date_start, $request->task_time_start);
        $fulldate_end = Converter::getFullDate($request->task_date_end, $request->task_time_end);

        $header = Task::create([
            'slug_name' => $slug, 
            'task_title' => $request->task_title,
            'task_desc' => $request->task_desc,
            'task_date_start' => $fulldate_start,
            'task_date_end' => $fulldate_end,
            'task_reminder' => $request->task_reminder,

            'created_at' => date("Y-m-d H:i"),
            'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002', //for now
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
                        'archive_id' => $request->archive_rel[$i],
                        'content_id' => $header->id,
                        'created_at' => date("Y-m-d H:i"),
                        'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002' //for now
                    ]);
                }
            }
        }

        return redirect()->back()->with('success_message', 'Create item success');
    }

    public function add_content_task_relation($slug_name, $type){
        if($type == 0){
            $content = ContentHeader::select('id')
                ->where('slug_name', $slug_name)
                ->get();

            if(count($content) > 0){
                $id = $content['id'][0];

                ArchiveRelation::create([
                    'archive_id' => $request->archive_id,
                    'content_id' => $id,
                    'created_at' => date("Y-m-d H:i"),
                    'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002' //for now
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
    

    // ================================= API =================================
    public function getContentHeader(){
        $content = ContentHeader::select('slug_name','content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
            //->whereRaw('DATE(content_date_start) = ?', date("Y-m-d")) //For now, just testing.
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->orderBy('contents_headers.created_at', 'DESC')
            ->paginate(12);
        
        return response()->json($content);
    }

    public function getAllNotification(){
        $user_id = 1;
        
        $notification = Notification::select('notif_type', 'notif_body', 'notif_send_to', 'is_pending')
            ->where('is_pending', 0)
            ->where(function ($query) {
                $query->where('notif_send_to','LIKE','%send_to":"1"%') //Must use jsoncontains
                    ->orWhere('notif_send_to','LIKE','%send_to":"all"%');
            })
            ->get();
        
        return response()->json([
            "msg"=> count($notification)." Data retrived", 
            "status"=> 200,
            "data"=> $notification
        ]);
    }
    
    public function getAllContent()
    {
        $cnt = content::orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(15);
        //Need pagination?
        return response()->json($cnt);
    }
    
    public function getContent($id)
    {
        $cnt = content::where('id', $id)->get();
        
        return response()->json($cnt);
    }

    public function getAllSchedule($date)
    {
        $cnt = content::selectRaw('id, content_title, content_desc, content_loc, content_tag, content_date_start, content_date_end, 1 as data_from')
            ->whereRaw("date(`content_date_start`) = ?", $date)
            ->orderBy('content.content_date_start', 'DESC');
            
        $sch = task::selectRaw('id, task_title as content_title, task_desc as content_desc, null as content_loc, null as content_tag, task_date_start as content_date_start, task_date_end as content_date_end, 2 as data_from')
            ->where('id_user', 1)
            ->whereRaw("date(`task_date_start`) = ?", $date)
            ->orderBy('task.task_date_start', 'DESC')
            ->union($cnt)
            ->get();
            
        //Need pagination?
        return response()->json($sch);
    }
    
    public function getAllTag()
    { 
        $tag = tag::orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->get();
        
        return response()->json($tag);
    }
    
    public function getMyArchieve($id_user)
    {
        // $ar = archieve::where('id_user', $id_user)->get();
        // Old select raw
        // archieve::selectRaw("archieve.id, archieve.archieve_name,  CASE WHEN content.content_type = 'event' THEN COUNT(content.id) ELSE 0 END AS event, CASE WHEN content.content_type = 'task' THEN COUNT(content.id) ELSE 0 END AS task")
        $ar = archieve::selectRaw("archieve.id, archieve.archieve_name,  CASE WHEN archieve_relation.rel_type = 'content' THEN COUNT(content.id) ELSE 0 END AS event, CASE WHEN archieve_relation.rel_type = 'task' THEN COUNT(content.id) ELSE 0 END AS task")
            ->leftjoin('archieve_relation', 'archieve.id', '=', 'archieve_relation.archieve_id')
            ->leftjoin('content', 'content.id', '=', 'archieve_relation.content_id')
            // ->join('task', 'task.id', '=', 'archieve_relation.content_id')
            ->where('archieve.id_user', $id_user)
            ->groupBy('archieve.id')
            ->orderBy('archieve.created_at', 'DESC')->get();
        return response()->json($ar);
    }

    public function getMySchedule(Request $request, $id){
        $sch = content::selectRaw('content.id, content_title, content_subtitle, content_desc, content_attach, content_tag, content_loc, content_date_start, content_date_end, content.created_at, content.updated_at, archieve_relation.id as id_rel')
            ->join('archieve_relation', 'archieve_relation.content_id', '=', 'content.id')
            ->where('archieve_relation.archieve_id', $id)
            ->orderBy('archieve_relation.created_at', 'DESC')
            ->get();
        
        return response()->json($sch);
    }

    public function addContent(Request $request, $id_user)
    {
        $result = content::create([
            'id_user' => $id_user,
            'content_title' => $request->content_title,
            'content_subtitle' => $request->content_subtitle,
            'content_desc' => $request->content_desc,
            'content_attach' => $request->content_attach,
            'content_tag' => $request->content_tag,
            'content_loc' => $request->content_loc,
            'content_date_start' => $request->content_date_start,
            'content_date_end' => $request->content_date_end,
            'created_at' => date("Y-m-d h:i"),
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Content successfully added',
            'result' => $result,
        ]);
    }

    public function addArchive(Request $request, $id_user)
    {
        $result = archieve::create([
            'id_user' => $id_user,
            'archieve_name' => $request->archieve_name,
            'created_at' => date("Y-m-d h:i"),
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Archive successfully added',
            'result' => $result,
        ]);
    }

    public function editArchive(Request $request, $id)
    {
        //Validate name avaiability
        $check = archieve::where('archieve_name', $request->archieve_name)->where('id_user', $request->id_user)->get();

        if(count($check) == 0){
            $result = archieve::where('id', $id)->update([
                'archieve_name' => $request->archieve_name,
                'updated_at' => date("Y-m-d h:i")
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Archive successfully updated',
                'result' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Archive name must be unique',
                'result' => null,
            ]);
        }
    }
    
    public function deleteArchive(Request $request, $id)
    {
        $result = archieve::destroy($id);
        
        //Delete archive relation
        DB::table('archieve_relation')->where('archieve_id', $id)->where('user_id', $request->user_id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Archive successfully deleted',
            'result' => $result,
        ]);
    }
    
    public function getMyTask()
    {
        $tk = task::orderBy('task_date_end', 'DESC')->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate(15);
        //Need pagination?
        return response()->json($tk);
    }
    
    public function updateTask(Request $request, $id){
        $result = task::where('id', $id)->update([
            'task_title' => $request->task_title,
            'task_desc' => $request->task_desc,
            'task_date_start' => $request->task_date_start,
            'task_date_end' => $request->task_date_end,
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task successfully updated',
            'result' => $result,
        ]);
    }
    
    public function addTask(Request $request, $id_user)
    {
        $result = task::create([
            'id_user' => $id_user,
            'task_title' => $request->task_title,
            'task_desc' => $request->task_desc,
            'task_date_start' => $request->task_date_start,
            'task_date_end' => $request->task_date_end,
            'created_at' => date("Y-m-d h:i"),
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Archive successfully added',
            'result' => $result,
        ]);
    }
}

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
use App\Models\ContentViewer;
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
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
            $type = ["Reminder", "Attachment"];
            
            if(!session()->get('selected_tag_calendar')){
                session()->put('selected_tag_calendar', "All");
            }
            if(!session()->get('ordering_event')){
                session()->put('ordering_event', "DESC");
            }
            if(!session()->get('filtering_date')){
                session()->put('filtering_date', "all");
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
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
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

    public function add_content_view($slug_name){
        $content_id = Generator::getContentId($slug_name);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        ContentViewer::create([
            'content_id' => $content_id,
            'type_viewer' => 0,
            'created_at' => date("Y-m-d H:i"),
            'created_by' => $user_id
        ]);

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

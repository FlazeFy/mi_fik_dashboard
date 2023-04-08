<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\ContentHeader;
use App\Models\Archive;
use App\Models\ArchiveRelation;
use App\Models\Tag;
use App\Models\History;
use App\Models\Menu;
use App\Models\Info;

class DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug_name)
    {
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

            $tag = Tag::getFullTag("DESC", "DESC");
            $content = ContentHeader::getFullContentBySlug($slug_name);
            $archive = Archive::getMyArchive($user_id, "DESC");
            $archive_relation = ArchiveRelation::getMyArchiveRelationBySlug($slug_name, $user_id);
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();
            $info = Info::getAvailableInfo("event/detail");

            //Set active nav
            session()->put('active_nav', 'event');
            $title = $content[0]['content_title'];

            return view ('event.detail.index')
                ->with('tag', $tag)
                ->with('content', $content)
                ->with('title', $title)
                ->with('archive', $archive)
                ->with('menu', $menu)
                ->with('info', $info)
                ->with('archive_relation', $archive_relation)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function add_relation(Request $request, $slug_name)
    {
        $content_id = ContentHeader::getContentIdBySlug($slug_name);

        ArchiveRelation::create([
            'id' => Generator::getUUID(),
            'archive_id' => $request->archive_id,
            'content_id' => $content_id,
            'created_at' => date("Y-m-d H:i"),
            'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002' //for now
        ]);

        return redirect()->back()->with('success_message', 'Content has been added to archive');
    }

    public function delete_relation($id){
        ArchiveRelation::destroy($id);

        return redirect()->back()->with('success_message', "Content has been removed from archive");
    }

    public function add_archive(Request $request){
        $slug = Generator::getSlugName($request->archive_name, "archive");

        Archive::create([
            'id' => Generator::getUUID(),
            'slug_name' => $slug,
            'archive_name' => $request->archive_name,
            'archive_desc' => null,
            'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002', //for now
            'created_at' => date('Y-m-d H:i:s'),
            'updated_by' => null,
            'updated_at' => null
        ]);

        return redirect()->back()->with('success_message', "Archive has been created");
    }

    public function delete_event($slug_name){
        $id = Generator::getContentId($slug_name);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        if($id != null){
            $data = new Request();
            $obj = [
                'history_type' => "event",
                'history_body' => "Has deleted this event"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                ContentHeader::where('id', $id)->update([
                    'deleted_at' => date("Y-m-d h:i:s"),
                    'deleted_by' => $user_id
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $id, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);

                return redirect()->route('homepage')->with('success_message', "Event has been deleted");
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");   
        }
    }
}

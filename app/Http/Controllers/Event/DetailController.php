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
use App\Models\User;

class DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug_name)
    {
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($user_id != null){
            $access = false;
            $content = ContentHeader::getFullContentBySlug($slug_name);

            if($content){
                if($role == 0){
                    $user = User::select("role")
                        ->where("id",$user_id)
                        ->first();

                    foreach($content as $ct){
                        foreach($ct->content_tag as $tags){
                            foreach($user->role as $role){
                                if($tags['slug_name'] == $role['slug_name']){
                                    $access = true;
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $access = true;
                }

                if($access){
                    $greet = Generator::getGreeting(date('h'));
                    $menu = Menu::getMenu();
                    $tag = Tag::getFullTag("DESC", "DESC");
                    $info = Info::getAvailableInfo("event/detail");
       
                    //Set active nav
                    session()->put('active_nav', 'event');
                    $title = $content[0]->content_title;
                    
                    return view ('event.detail.index')
                        ->with('tag', $tag)
                        ->with('content', $content)
                        ->with('title', $title)
                        ->with('menu', $menu)
                        ->with('info', $info)
                        ->with('greet',$greet);
                } else {
                    return view("errors.403");
                }
            } else {
                return view("errors.404");
            }
        } else {
            return redirect("/")->with('failed_message','Session lost, please sign in again');
        }
    }

    public function add_relation(Request $request, $slug_name)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));
        $content_id = ContentHeader::getContentIdBySlug($slug_name);

        ArchiveRelation::create([
            'id' => Generator::getUUID(),
            'archive_id' => $request->archive_id,
            'content_id' => $content_id,
            'created_at' => date("Y-m-d H:i"),
            'created_by' => $user_id
        ]);

        return redirect()->back()->with('success_message', 'Content has been added to archive');
    }

    public function delete_relation($id){
        ArchiveRelation::destroy($id);

        return redirect()->back()->with('success_message', "Content has been removed from archive");
    }

    public function add_archive(Request $request){
        $slug = Generator::getSlugName($request->archive_name, "archive");
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        Archive::create([
            'id' => Generator::getUUID(),
            'slug_name' => $slug,
            'archive_name' => $request->archive_name,
            'archive_desc' => null,
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_by' => null,
            'updated_at' => null
        ]);

        return redirect()->back()->with('success_message', "Archive has been created");
    }

    public function delete_event($slug_name){
        $id = Generator::getContentId($slug_name);
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

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
                    'deleted_at' => date("Y-m-d H:i:s"),
                    'deleted_by' => $user_id
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $id, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);

                return redirect()->route('homepage')->with('success_message', "Event has been deleted");
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");   
        }
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Validation;
use App\Helpers\Generator;
use App\Helpers\FirebaseTask;

use App\Models\Setting;
use App\Models\Menu;
use App\Models\History;
use App\Models\SettingSystem;
use App\Models\ContentHeader;
use App\Models\ContentViewer;
use App\Models\ArchiveRelation;
use App\Models\UserGroup;
use App\Models\FailedJob;
use App\Models\Task;
use App\Models\Tag;
use App\Models\Info;
use App\Models\Feedback;
use App\Models\Dictionary;
use App\Models\Question;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role);

        if($user_id != null){
            $settingJobs = SettingSystem::getJobsSetting();
            $menu = Menu::getMenu();
            $info = Info::getAvailableInfo("trash");
            
            //Set active nav
            session()->put('active_nav', 'trash');
            session()->forget('active_subnav');

            return view ('trash.index')
                ->with('menu', $menu)
                ->with('info', $info)
                ->with('settingJobs', $settingJobs);
        } else {
            return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
        }
    }

    public function set_ordering_content($order)
    {
        session()->put('ordering_trash', $order);

        return redirect()->back()->with('success_message', 'Content ordered');
    }

    public function recover_content($slug, $type)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        if($type == 1){
            $type = "event";
            $res = ContentHeader::select("id")->where("slug_name",$slug)->first();
            $res = $res->id;
        } else if($type == 2){
            $type = "task";
            $res = Task::select("id")->where("slug_name",$slug)->first();
            $res = $res->id;
        } else if($type == 3){
            $type = "tag";
            $res = Tag::select("id")->where("slug_name",$slug)->first();
            $res = $res->id;
        } else if($type == 4){
            $type = "group";
            $res = UserGroup::select("id")->where("slug_name",$slug)->first();
            $res = $res->id;
        } else if($type == 5){
            $type = "info";
            $res = $slug;
        } else if($type == 6){
            $type = "feedback";
            $res = $slug;
        } else if($type == 7){
            $type = "dictionary";
            $res = $slug;
        } else if($type == 8){
            $type = "question";
            $res = $slug;
        }

        if($slug != null && $type != null){
            $data = new Request();
            $obj = [
                'history_type' => $type,
                'history_body' => "Has recover this ".$type
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                if($type == "event"){
                    ContentHeader::where('id', $res)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "task"){
                    Task::where('id', $res)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "tag"){
                    Tag::where('id', $res)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "group"){
                    UserGroup::where('id', $res)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "info"){
                    Info::where('id', $res)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "feedback"){
                    Feedback::where('id', $res)->update([
                        'deleted_at' => null,
                    ]);
                } else if($type == "dictionary"){
                    Dictionary::where('id', $res)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "question"){
                    Question::where('id', $res)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                }

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $res, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', ucfirst($type)." successfully recover");    
            }
        } else {
            return redirect()->back()->with('failed_message', ucfirst($type)." recover is failed, the event doesn't exist anymore");
        }
    }

    public function destroy_content(Request $request, $slug, $type)
    {
        DB::beginTransaction();

        try{
            $user_id = Generator::getUserIdV2(session()->get('role_key'));

            if($type == 1){
                $type = "event";
                $id = Generator::getContentId($slug);
                $owner = Generator::getContentOwner($slug);
            } else if($type == 2){
                $type = "task";
                $id = Generator::getTaskId($slug);
                $owner = Generator::getTaskOwner($slug);
            } else if($type == 3){
                $type = "tag";
                $item = DB::table("tags")
                    ->select("id","created_by")
                    ->where("slug_name",$slug)
                    ->first();
                $id = $item->id;
                $owner = $item->created_by;
            } else if($type == 4){
                $type = "group";
                $item = DB::table("users_groups")
                    ->select("id","created_by")
                    ->where("slug_name",$slug)
                    ->first();
                $id = $item->id;
                $owner = $item->created_by;
            } else if($type == 5){
                $type = "info";
                $item = DB::table("infos")
                    ->select("created_by")
                    ->where("id",$slug)
                    ->first();
                $id = $slug;
                $owner = $item->created_by;
            } else if($type == 7){
                $type = "dictionary";
                $item = DB::table("dictionaries")
                    ->select("created_by")
                    ->where("id",$slug)
                    ->first();
                $id = $slug;
                $owner = $item->created_by;
            } else if($type == 6){
                $type = "feedback";
                $id = $slug;
                $owner = null;
            } else if($type == 8){
                $type = "question";
                $id = $slug;
                $owner = null;
            }

            if($slug != null && $type != null){
                $data = new Request();
                $obj = [
                    'history_type' => $type,
                    'history_body' => 'Has permanentaly delete a '.$type.' called "'.$request->content_title.'"'
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails() && $type != "feedback" && $type != "question") {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    if($type == "event"){
                        FirebaseTask::deleteContentAttachment($id);

                        DB::table("contents_headers")->where('id', $id)->delete();
                        DB::table("contents_details")->where('content_id', $id)->delete();
                        DB::table("archives_relations")->where('content_id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'event')->delete();
                        DB::table("contents_viewers")->where('content_id', $id)->where('type_viewer', 0)->delete();
                    } else if($type == "task"){
                        DB::table("tasks")->where('id', $id)->delete();
                        DB::table("archives_relations")->where('content_id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'task')->delete();
                    } else if($type == "tag"){
                        DB::table("tags")->where('id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'tag')->delete();
                    } else if($type == "group"){
                        DB::table("users_groups")->where('id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'group')->delete();
                    } else if($type == "info"){
                        DB::table("infos")->where('id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'info')->delete();
                    } else if($type == "feedback"){
                        DB::table("feedbacks")->where('id', $id)->delete();
                    } else if($type == "question"){
                        DB::table("questions")->where('id', $id)->delete();
                    } else if($type == "dictionary"){
                        DB::table("dictionaries")->where('id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'dictionary')->delete();
                    }

                    if($type != "feedback" && $type != "questions"){
                        DB::table("histories")->insert([
                            'id' => Generator::getUUID(),
                            'history_type' => $data->history_type, 
                            'context_id' => null, 
                            'history_body' => $data->history_body, 
                            'history_send_to' => $owner,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id
                        ]);
                    }
                    
                    DB::commit();
                    return redirect()->back()->with('success_message', ucfirst($type)." successfully destroyed");    
                }
            } else {
                return redirect()->back()->with('failed_message', ucfirst($type)." destroy is failed, the event doesn't exist anymore");
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Destroy is failed '.$e);
        }
    }
}

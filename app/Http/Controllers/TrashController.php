<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Validation;
use App\Helpers\Generator;

use App\Models\Setting;
use App\Models\Menu;
use App\Models\History;
use App\Models\SettingSystem;
use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\ContentViewer;
use App\Models\ArchiveRelation;
use App\Models\Task;
use App\Models\Info;

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
            $greet = Generator::getGreeting(date('h'));
            $settingJobs = SettingSystem::getJobsSetting();
            $menu = Menu::getMenu();
            $info = Info::getAvailableInfo("trash");
            
            //Set active nav
            session()->put('active_nav', 'trash');
            session()->forget('active_subnav');

            return view ('trash.index')
                ->with('menu', $menu)
                ->with('info', $info)
                ->with('settingJobs', $settingJobs)
                ->with('greet',$greet);
        } else {
            return redirect("/")->with('failed_message','Session lost, try to sign in again');
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
            $id = Generator::getContentId($slug);
        } else if($type == 2){
            $type = "task";
            $id = Generator::getTaskId($slug);
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
                    ContentHeader::where('id', $id)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "task"){
                    Task::where('id', $id)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                }

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $id, 
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
            }

            if($slug != null && $type != null){
                $data = new Request();
                $obj = [
                    'history_type' => $type,
                    'history_body' => 'Has destroy a '.$type.' called "'.$request->content_title.'"'
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    if($type == "event"){
                        DB::table("contents_headers")->where('id', $id)->delete();
                        DB::table("contents_details")->where('content_id', $id)->delete();
                        DB::table("archives_relations")->where('content_id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'event')->delete();
                        ContentViewer::where('content_id', $id)->where('type_viewer', 0)->delete();
                    } else if($type == "task"){
                        DB::table("tasks")->where('id', $id)->delete();
                        DB::table("archives_relations")->where('content_id', $id)->delete();
                        DB::table("histories")->where('context_id', $id)->where('history_type', 'task')->delete();
                    }

                    DB::table("histories")->insert([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => null, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => $owner,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);
                    
                    DB::commit();
                    return redirect()->back()->with('success_message', ucfirst($type)." successfully destroyed");    
                }
            } else {
                return redirect()->back()->with('failed_message', ucfirst($type)." destroy is failed, the event doesn't exist anymore");
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', 'Create content failed '.$e);
        }
    }
}

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
use App\Models\ContentHeader;
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
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();
            $info = Info::getAvailableInfo("trash");
            
            //Set active nav
            session()->put('active_nav', 'trash');

            return view ('trash.index')
                ->with('menu', $menu)
                ->with('info', $info)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function set_ordering_content($order)
    {
        session()->put('ordering_trash', $order);

        return redirect()->back()->with('success_message', 'Content ordered');
    }

    public function recover_content($slug, $type)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

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
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', ucfirst($type)." successfully recover");    
            }
        } else {
            return redirect()->back()->with('failed_message', ucfirst($type)." update is failed, the event doesn't exist anymore");
        }
    }
}

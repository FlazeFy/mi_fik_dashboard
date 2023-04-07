<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\Tag;
use App\Models\Menu;
use App\Models\Info;
use App\Models\History;
use App\Models\Dictionary;

class EditController extends Controller
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
            $type = ["Reminder", "Attachment"];

            $tag = Tag::getFullTag("DESC", "DESC");
            $content = ContentHeader::getFullContentBySlug($slug_name);
            $greet = Generator::getGreeting(date('h'));
            $dictionary = Dictionary::getDictionaryByType($type);
            $history = History::getContentHistory($slug_name);
            $menu = Menu::getMenu();
            $info = Info::getAvailableInfo("event/edit");

            //Set active nav
            session()->put('active_nav', 'event');
            $title = $content[0]['content_title'];

            return view ('event.edit.index')
                ->with('tag', $tag)
                ->with('content', $content)
                ->with('title', $title)
                ->with('menu', $menu)
                ->with('info', $info)
                ->with('history', $history)
                ->with('dictionary', $dictionary)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function update_event_info(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        $validator = Validation::getValidateEventInfo($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "event",
                'history_body' => "Has updated this event info"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                ContentHeader::where('id', $id)->update([
                    'content_title' => $request->content_title,
                    'content_desc' => $request->content_desc,
                    'updated_at' => date("Y-m-d h:i:s"),
                    'updated_by' => $user_id
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
            }

            return redirect()->back()->with('success_message', "Event successfully updated");             
        }
    }

    public function update_event_draft(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        if($request->is_draft == 1){
            $body = "Has set this event as draft";
        } else {
            $body = "Has unset this event from draft";
        }

        $data = new Request();
        $obj = [
            'history_type' => "event",
            'history_body' => $body
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            ContentHeader::where('id', $id)->update([
                'is_draft' => $request->is_draft,
                'updated_at' => date("Y-m-d h:i:s"),
                'updated_by' => $user_id
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

            return redirect()->back()->with('success_message', "Event successfully updated");             
        }
    }

    public function update_event_add_attach(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $id_detail = Generator::getContentDetailId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        $att = Generator::getContentAtt($id_detail);
        $arrobj = json_decode($att);

        if($att && json_last_error() === JSON_ERROR_NONE){
            $data = new Request();
            $obj = [
                'history_type' => "event",
                'history_body' => "Has added new attachment"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $obj = $request->content_attach;
                $obj = json_decode($obj);
                $newobj = json_encode(array_merge($arrobj, $obj));

                if(json_last_error() === JSON_ERROR_NONE){
                    ContentDetail::where('id', $id_detail)->update([
                        'content_attach' => $newobj,
                    ]);

                    ContentHeader::where('id', $id)->update([
                        'updated_at' => date("Y-m-d h:i:s"),
                        'updated_by' => $user_id
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
    
                    return redirect()->back()->with('success_message', "Event successfully updated"); 
                } else {
                    return redirect()->back()->with('failed_message', "Attachment is invalid");    
                }
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");    
        }
        
    }

    public function update_event_remove_attach(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $id_detail = Generator::getContentDetailId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        $att = Generator::getContentAtt($id_detail);
        $oldobj = json_decode($att);

        if($att && json_last_error() === JSON_ERROR_NONE){
            $data = new Request();
            $obj = [
                'history_type' => "event",
                'history_body' => "Has removed attachment"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                foreach ($oldobj as $index => $object) {
                    if ($object->id == $request->attachment_id) {
                        $index = $index;
                        break;
                    }
                }
                if ($index !== null) {
                    array_splice($oldobj, $index, 1);
                }
                $newobj = json_encode($oldobj);

                if(json_last_error() === JSON_ERROR_NONE){
                    ContentDetail::where('id', $id_detail)->update([
                        'content_attach' => $newobj,
                    ]);

                    ContentHeader::where('id', $id)->update([
                        'updated_at' => date("Y-m-d h:i:s"),
                        'updated_by' => $user_id
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
    
                    return redirect()->back()->with('success_message', "Event successfully updated"); 
                } else {
                    return redirect()->back()->with('failed_message', "Attachment is invalid");    
                }
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");    
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Converter;

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
                
    }

    public function update_event_info(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        if($id != null){
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
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");   
        }
    }

    public function update_event_draft(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        if($id != null){
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
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");   
        }
    }

    public function update_event_add_attach(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $id_detail = Generator::getContentDetailId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        $att = Generator::getContentAtt($id_detail);
        $arrobj = json_decode($att);

        if($id != null && $id_detail != null){
            if(($att && json_last_error() === JSON_ERROR_NONE) || $att == null){
                $data = new Request();
                $obj = [
                    'history_type' => "event",
                    'history_body' => "Has added a new attachment"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    $obj = $request->content_attach;

                    if($att != null){
                        $obj = json_decode($obj);
                        $newobj = json_encode(array_merge($arrobj, $obj));
                    } else {
                        $newobj = $obj;
                    }

                    if(json_last_error() === JSON_ERROR_NONE || $att == null){
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
                return redirect()->back()->with('failed_message', "Attachment is invalid");    
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

        if($id != null && $id_detail != null){
            if($att && json_last_error() === JSON_ERROR_NONE){
                $data = new Request();
                $obj = [
                    'history_type' => "event",
                    'history_body' => "Has removed an attachment"
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
                return redirect()->back()->with('failed_message', "Attachment is invalid");      
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore"); 
        }
    }

    public function update_event_remove_tag(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $id_detail = Generator::getContentDetailId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        $tag =  ContentDetail::getContentTag($id_detail);
        $oldobj = json_decode($tag);

        if($id != null && $id_detail != null){
            if($tag && json_last_error() === JSON_ERROR_NONE){
                $data = new Request();
                $obj = [
                    'history_type' => "event",
                    'history_body' => "Has removed a tag"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    foreach ($oldobj as $index => $object) {
                        if ($object->slug_name == $request->slug_name) {
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
                            'content_tag' => $newobj,
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
                        return redirect()->back()->with('failed_message', "Tag is invalid");    
                    }
                }
            } else {
                return redirect()->back()->with('failed_message', "Tag is invalid");     
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");  
        }
    }

    public function update_event_add_tag(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $id_detail = Generator::getContentDetailId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        $tag =  ContentDetail::getContentTag($id_detail);
        $oldobj = json_decode($tag);

        if($id != null && $id_detail != null){
            if($tag && json_last_error() === JSON_ERROR_NONE || $tag == null){
                $data = new Request();
                $obj = [
                    'history_type' => "event",
                    'history_body' => "Has add a new tag"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    $tagNew = Converter::getTag($request->tag);
                    if($tag != null){
                        $tagParsed = json_decode($tagNew);
                        $newobj = json_encode(array_merge($oldobj, $tagParsed));
                    } else {
                        $newobj = $tagNew;
                    }

                    if(json_last_error() === JSON_ERROR_NONE || $tag == null){
                        ContentDetail::where('id', $id_detail)->update([
                            'content_tag' => $newobj,
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
                        return redirect()->back()->with('failed_message', "Tag is invalid");    
                    }
                }
            } else {
                return redirect()->back()->with('failed_message', "Tag is invalid"); 
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");    
        }
    }

    public function update_event_add_loc(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $id_detail = Generator::getContentDetailId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        if($id != null && $id_detail != null){
            $data = new Request();
            $obj = [
                'history_type' => "event",
                'history_body' => "Has updated event location"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $check = Validation::getValidateJSON($request->content_loc);

                if($check){
                    ContentDetail::where('id', $id_detail)->update([
                        'content_loc' => $request->content_loc,
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
                    return redirect()->back()->with('failed_message', "Location is invalid");    
                }
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");    
        }
    }

    public function update_event_remove_loc(Request $request, $slug)
    {
        $id = Generator::getContentId($slug);
        $id_detail = Generator::getContentDetailId($slug);
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));

        if($id != null && $id_detail != null){
            $data = new Request();
            $obj = [
                'history_type' => "event",
                'history_body' => "Has removed event location"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                ContentDetail::where('id', $id_detail)->update([
                    'content_loc' => null,
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
            }
        } else {
            return redirect()->back()->with('failed_message', "Event update is failed, the event doesn't exist anymore");    
        }
    }
}

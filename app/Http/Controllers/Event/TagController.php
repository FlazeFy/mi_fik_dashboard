<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Helpers\Converter;
use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\ContentDetail;
use App\Models\Tag;
use App\Models\User;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\Dictionary;
use App\Models\History;
use App\Models\Info;

class TagController extends Controller
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
            $tag = Tag::getFullTagByCat(session()->get('selected_tag_category'));

            if($role == 1){
                $user_id = Generator::getUserIdV2(1);
                $setting = Setting::getSingleSetting("MOT_range", $user_id);
            }

            //Chart query
            if($role == 1){
                $mostTag = ContentDetail::getMostUsedTag();
                $history = History::getHistoryByType("tag");
            }
            $menu = Menu::getMenu();
            $dct_tag = Dictionary::getDictionaryByType("Tag");
            $info = Info::getAvailableInfo("event/tag");

            //Set active nav
            session()->put('active_nav', 'event');
            session()->put('active_subnav', 'tag');

            if($role == 1){
                return view ('event.tag.index')
                    ->with('mostTag', $mostTag)
                    ->with('tag', $tag)
                    ->with('dct_tag', $dct_tag)
                    ->with('menu', $menu)
                    ->with('setting', $setting)
                    ->with('history', $history)
                    ->with('info', $info);
            } else {
                $list = User::getUserRole($user_id,$role);

                foreach($list as $l){
                    $mytag = $l->role;
                }

                return view ('event.tag.index')
                    ->with('tag', $tag)
                    ->with('dct_tag', $dct_tag)
                    ->with('menu', $menu)
                    ->with('mytag', $mytag)
                    ->with('info', $info);
            }
        } else {
            return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
        }
    }

    public function update_tag(Request $request, $type, $id)
    {
        //Validate name avaiability
        $check = Tag::where('tag_name', $request->tag_name)->get();

        if((count($check) == 0 || $request->update_type == "desc" || $request->update_type == "cat") && strtolower(str_replace(" ","", $request->tag_name)) != "all"){
            $slug = Generator::getSlugName($request->tag_name, "tag");

            $user_id = Generator::getUserIdV2(session()->get('role_key')); 

            $validator = Validation::getValidateTag($request, $type);
            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "tag",
                    'history_body' => "Has updated '".$request->tag_name."' tag"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    if($type == "desc"){
                        Tag::where('id', $id)->update([
                            'tag_desc' => $request->tag_desc,
                            'updated_at' => date("Y-m-d H:i:s"),
                            'updated_by' => $user_id
                        ]);
                    } else if($type == "cat"){
                        Tag::where('id', $id)->update([
                            'tag_category' => $request->tag_category,
                            'updated_at' => date("Y-m-d H:i:s"),
                            'updated_by' => $user_id
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

                    return redirect()->back()->with('success_message', "'".$request->tag_name."' tag has been successfully updated");
                }
            }
        } else {
            return redirect()->back()->with('failed_message', 'Updated tag failed. Please use unique name');
        }
    }

    public function delete_tag(Request $request, $id)
    {
        $data = new Request();
        $obj = [
            'history_type' => "tag",
            'history_body' => "Has deleted '".$request->tag_name."' tag"
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $user_id = Generator::getUserIdV2(session()->get('role_key')); 

            Tag::where('id', $id)->update([
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
        }

        return redirect()->back()->with('success_message', 'Tag has been deleted');
    }

    public function add_tag(Request $request)
    {
        
            if(is_countable($request->tag_name)){
                $user_id = Generator::getUserIdV2(session()->get('role_key')); 
                $tag_count = count($request->tag_name);
                $success = 0;
                $failed = 0;
            
                for($i = 0; $i < $tag_count; $i++){
                    $tag_name = $request->tag_name[$i];
                   
                    $data = new Request();
                    $obj = [
                        'history_type' => "tag",
                        'history_body' => "Has created a tag called '".$tag_name."'"
                    ];
                    $data->merge($obj);
    
                    $validatorHistory = Validation::getValidateHistory($data);
                    if ($validatorHistory->fails()) {
                        $errors = $validatorHistory->messages();
    
                        return redirect()->back()->with('failed_message', $errors);
                    } else {
                        //Validate name avaiability
                        $tag_desc = $request->tag_desc[$i];
                        $tag_cat = $request->tag_category[$i];
                        $check = Tag::where('tag_name', $tag_name)->first();

                        if($check == null && strtolower(str_replace(" ","", $tag_name)) != "all" && strtolower(str_replace(" ","", $tag_name)) != "lecturer" 
                            && strtolower(str_replace(" ","", $tag_name)) != "student" && strtolower(str_replace(" ","", $tag_name)) != "staff"){
                            $slug = Generator::getSlugName($tag_name, "tag");

                            $tag_data = new Request();
                            $obj_tag = [
                                'tag_name' => $tag_name,
                                'tag_desc' => $tag_desc,
                                'tag_category' => $tag_cat,
                            ];
                            $tag_data->merge($obj_tag);

                            $validator = Validation::getValidateTag($tag_data, "all");
                            if ($validator->fails()) {
                                $failed++;
                            } else {
                                $header = Tag::create([
                                    'id' => Generator::getUUID(),
                                    'slug_name' => $slug,
                                    'tag_name' => $tag_name,
                                    'tag_desc' => $tag_desc,
                                    'tag_category' => $tag_cat,
                                    'created_at' => date("Y-m-d H:i:s"),
                                    'created_by' => $user_id,
                                    'updated_at' => null,
                                    'deleted_at' => null,
                                    'updated_by' => null,
                                    'deleted_by' => null
                                ]);

                                History::create([
                                    'id' => Generator::getUUID(),
                                    'history_type' => $data->history_type, 
                                    'context_id' => $header->id, 
                                    'history_body' => $data->history_body, 
                                    'history_send_to' => null,
                                    'created_at' => date("Y-m-d H:i:s"),
                                    'created_by' => $user_id
                                ]);

                                $success++;
                            }
                        } else {
                            $failed++;
                        }
                    }
                }

                if($success > 0 && $failed == 0){
                    return redirect()->back()->with('success_message', $success." Tag has been created");
                } else if($success > 0 && $failed > 0){
                    return redirect()->back()->with('success_message', $success." Tag has been created and ".$failed." Tag has failed to created");
                } else {
                    return redirect()->back()->with('failed_message', $failed." Tag has failed to created, Please use unique name");
                }
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "tag",
                    'history_body' => "Has created a tag called '".$request->tag_name."'"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    //Validate name avaiability
                    $check = Tag::where('tag_name', $request->tag_name)->first();

                    if($check == null && strtolower(str_replace(" ","", $request->tag_name)) != "all" && strtolower(str_replace(" ","", $request->tag_name)) != "lecturer" 
                        && strtolower(str_replace(" ","", $request->tag_name)) != "student" && strtolower(str_replace(" ","", $request->tag_name)) != "staff"){
                        $slug = Generator::getSlugName($request->tag_name, "tag");

                        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

                        $validator = Validation::getValidateTag($request, "all");
                        if ($validator->fails()) {
                            $errors = $validator->messages();

                            return redirect()->back()->with('failed_message', $errors);
                        } else {
                            $header = Tag::create([
                                'id' => Generator::getUUID(),
                                'slug_name' => $slug,
                                'tag_name' => $request->tag_name,
                                'tag_desc' => $request->tag_desc,
                                'tag_category' => $request->tag_category,
                                'created_at' => date("Y-m-d H:i:s"),
                                'created_by' => $user_id,
                                'updated_at' => null,
                                'deleted_at' => null,
                                'updated_by' => null,
                                'deleted_by' => null
                            ]);
        
                            History::create([
                                'id' => Generator::getUUID(),
                                'history_type' => $data->history_type, 
                                'context_id' => $header->id, 
                                'history_body' => $data->history_body, 
                                'history_send_to' => null,
                                'created_at' => date("Y-m-d H:i:s"),
                                'created_by' => $user_id
                            ]);

                            return redirect()->back()->with('success_message', "'".$request->tag_name."' Tag has been created");
                        } 
                    } else {
                        return redirect()->back()->with('failed_message', 'Please use unique name');
                    }
                }
            }            
    
    }

    public function add_tag_category(Request $request)
    {
        //Validate name avaiability
        $check = Dictionary::where('dct_name', $request->dct_name)->where('dct_type', 'TAG-001')->get();

        if(count($check) == 0){
            $slug = Generator::getSlugName($request->dct_name, "dct_tag");

            $user_id = Generator::getUserIdV2(session()->get('role_key')); 

            $validator = Validation::getValidateTag($request, "dct");
            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "tag",
                    'history_body' => "Has created a tag category called '".$request->dct_name."'"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    $header = Dictionary::create([
                        'id' => Generator::getUUID(),
                        'slug_name' => $slug,
                        'dct_name' => $request->dct_name,
                        'dct_desc' => $request->dct_desc,
                        'dct_type' => "TAG-001",
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'deleted_at' => null,
                        'updated_by' => null,
                        'deleted_by' => null
                    ]);

                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $header->id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);
                    return redirect()->back()->with('success_message', "'".$request->dct_name."' Tag Category has been created");
                }
            }
        } else {
            return redirect()->back()->with('failed_message', 'Please use unique name');
        }
    }

    public function delete_cat_tag(Request $request, $id)
    {
        DB::beginTransaction();

        try{
            $user_id = Generator::getUserIdV2(session()->get('role_key')); 

            $validator = Validation::getValidateTag($request, "dct");
            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $data = new Request();
                $obj = [
                    'history_type' => "tag",
                    'history_body' => "Has deleted '".$request->dct_name."' tag category"
                ];
                $data->merge($obj);

                $validatorHistory = Validation::getValidateHistory($data);
                if ($validatorHistory->fails()) {
                    $errors = $validatorHistory->messages();

                    return redirect()->back()->with('failed_message', $errors);
                } else {
                    $tags = DB::table("tags")->select("tag_name","tags.id")
                        ->join("dictionaries","dictionaries.slug_name","=","tags.tag_category")
                        ->where("dictionaries.id",$id)
                        ->get();
                    
                    foreach($tags as $tag){
                        DB::table("tags")->where("id",$tag->id)->update([
                            'tag_category' => $request->new_tag_category,
                            'updated_at' => date("Y-m-d H:i:s"),
                            'updated_by' => $user_id
                        ]);

                        DB::table("histories")->insert([
                            'id' => Generator::getUUID(),
                            'history_type' => $data->history_type, 
                            'context_id' => $tag->id, 
                            'history_body' => "Has updated '".$tag->tag_name."' tag",
                            'history_send_to' => null,
                            'created_at' => date("Y-m-d H:i:s"),
                            'created_by' => $user_id
                        ]);
                    }

                    DB::table("dictionaries")->where("id",$id)->delete();

                    DB::table("histories")->insert([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => null, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);

                    DB::commit();
                    return redirect()->back()->with('success_message', "'".$request->dct_name."' Tag Category has been deleted");
                }
            }
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null));
        }
    }

    public function filter_category(Request $request)
    {
        session()->put('selected_tag_category', $request->tag_category);

        return redirect()->back()->with('success_mini_message', 'Tag filtered');
    }
}

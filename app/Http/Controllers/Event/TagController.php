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
use App\Models\Menu;
use App\Models\Setting;
use App\Models\History;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tag = Tag::getFullTag("DESC", "DESC");

        if(session()->get('role_key') == 1){
            $user_id = Generator::getUserIdV2(1);
            $setting = Setting::getSingleSetting("MOT_range", $user_id);
        }

        //Chart query
        if(session()->get('role_key') == 1){
            $mostTag = ContentDetail::getMostUsedTag();
            $history = History::getHistoryByType("tag");
        }
        $greet = Generator::getGreeting(date('h'));
        $menu = Menu::getMenu();

        //Set active nav
        session()->put('active_nav', 'event');
        session()->put('active_subnav', 'tag');

        if(session()->get('role_key') == 1){
            return view ('event.tag.index')
                ->with('mostTag', $mostTag)
                ->with('tag', $tag)
                ->with('menu', $menu)
                ->with('setting', $setting)
                ->with('history', $history)
                ->with('greet',$greet);
        } else {
            return view ('event.tag.index')
                ->with('tag', $tag)
                ->with('menu', $menu)
                ->with('greet',$greet);
        }
    }

    public function update_tag(Request $request, $id)
    {
        //Validate name avaiability
        $check = Tag::where('tag_name', $request->tag_name)->get();

        if((count($check) == 0 || $request->update_type == "desc") && strtolower(str_replace(" ","", $request->tag_name)) != "all"){
            $slug = Generator::getSlugName($request->tag_name, "tag");

            $user_id = Generator::getUserIdV2(session()->get('role_key')); 

            $validator = Validation::getValidateTag($request);
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
                    Tag::where('id', $id)->update([
                        'slug_name' => $slug,
                        'tag_name' => $request->tag_name,
                        'tag_desc' => $request->tag_desc,
                        'tag_category' => $request->tag_category,
                        'updated_at' => date("Y-m-d h:i:s"),
                        'updated_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002'
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

                    return redirect()->back()->with('success_message', "'".$request->tag_name."' tag has been successfully updated");
                }
            }
        } else {
            return redirect()->back()->with('failed_message', 'Updated tag failed. Please use unique name');
        }
    }

    public function delete_tag($id)
    {
        Tag::destroy($id);

        return redirect()->back()->with('success_message', 'Tag has been deleted');
    }

    public function add_tag(Request $request)
    {
        //Validate name avaiability
        $check = Tag::where('tag_name', $request->tag_name)->get();

        if(count($check) == 0 && strtolower(str_replace(" ","", $request->tag_name)) != "all"){
            $slug = Generator::getSlugName($request->tag_name, "tag");

            $user_id = Generator::getUserIdV2(session()->get('role_key')); 

            $validator = Validation::getValidateTag($request);
            if ($validator->fails()) {
                $errors = $validator->messages();

                return redirect()->back()->with('failed_message', $errors);
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
                    $header = Tag::create([
                        'id' => Generator::getUUID(),
                        'slug_name' => $slug,
                        'tag_name' => $request->tag_name,
                        'tag_desc' => $request->tag_desc,
                        'tag_category' => $request->tag_category,
                        'created_at' => date("Y-m-d h:i:s"),
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
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);
                    return redirect()->back()->with('success_message', "'".$request->tag_name."' Tag has been created");
                }
            }
        } else {
            return redirect()->back()->with('failed_message', 'Create tag failed. Please use unique name');
        }
    }
}

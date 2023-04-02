<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Helpers\Converter;
use App\Helpers\Generator;

use App\Models\ContentDetail;
use App\Models\Tag;
use App\Models\Menu;
use App\Models\Setting;

class TagController extends Controller
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
            $tag = Tag::getFullTag("DESC", "DESC");
            $setting = Setting::getSingleSetting("MOT_range", $user_id);

            //Chart query
            $mostTag = ContentDetail::getMostUsedTag();
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();

            //Set active nav
            session()->put('active_nav', 'event');

            return view ('event.tag.index')
                ->with('mostTag', $mostTag)
                ->with('tag', $tag)
                ->with('menu', $menu)
                ->with('setting', $setting)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function update_tag(Request $request, $id)
    {
        //Validate name avaiability
        $check = Tag::where('tag_name', $request->tag_name)->get();

        if((count($check) == 0 || $request->update_type == "desc") && strtolower(str_replace(" ","", $request->tag_name)) != "all"){
            $slug = Generator::getSlugName($request->tag_name, "tag");

            Tag::where('id', $id)->update([
                'slug_name' => $slug,
                'tag_name' => $request->tag_name,
                'tag_desc' => $request->tag_desc,
                'updated_at' => date("Y-m-d h:i:s"),
                'updated_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002'
            ]);
    
            return redirect()->back()->with('success_message', "'".$request->tag_name."' tag has been successfully updated");
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

            Tag::create([
                'id' => Generator::getUUID(),
                'slug_name' => $slug,
                'tag_name' => $request->tag_name,
                'tag_desc' => $request->tag_desc,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => null,
                'deleted_at' => null,
                'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002',
                'updated_by' => null,
                'deleted_by' => null
            ]);

            return redirect()->back()->with('success_message', "'".$request->tag_name."' Tag has been created");
        } else {
            return redirect()->back()->with('failed_message', 'Create tag failed. Please use unique name');
        }
    }
}

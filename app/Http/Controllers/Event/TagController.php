<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Models\content;
use App\Models\tag;
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
        $tag = Tag::all();

        //Chart query
        $mostTag = Content::select('content_tag')
            ->whereNot('content_tag', null)
            ->get();

        $setting = Setting::select('id', 'MOT_range')
            ->where('id_user', 1)
            ->get();

        //Set active nav
        session()->put('active_nav', 'event');

        return view ('event.tag.index')
            ->with('mostTag', $mostTag)
            ->with('tag', $tag)
            ->with('setting', $setting);
    }

    public function update_tag(Request $request, $id)
    {
        //Validate name avaiability
        $check = Tag::where('tag_name', $request->tag_name)->get();

        if(count($check) == 0){
            Tag::where('id', $id)->update([
                'tag_name' => $request->tag_name,
                'updated_at' => date("Y-m-d h:i:s"),
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

        if(count($check) == 0){
            Tag::create([
                'tag_name' => $request->tag_name,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            ]);

            return redirect()->back()->with('success_message', "'".$request->tag_name."'Tag has been created");
        } else {
            return redirect()->back()->with('failed_message', 'Create tag failed. Please use unique name');
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

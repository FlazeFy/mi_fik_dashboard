<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;

use App\Models\ContentHeader;
use App\Models\Archive;
use App\Models\ArchiveRelation;
use App\Models\Tag;

class DetailController extends Controller
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

            $tag = Tag::getFullTag("DESC", "DESC");
            $content = ContentHeader::getFullContentBySlug($slug_name);
            $archive = Archive::getMyArchive($user_id, "DESC");
            $archive_relation = ArchiveRelation::getMyArchiveRelationBySlug($slug_name, $user_id);

            //Set active nav
            session()->put('active_nav', 'event');
            $title = $content[0]['content_title'];

            return view ('event.detail.index')
                ->with('tag', $tag)
                ->with('content', $content)
                ->with('title', $title)
                ->with('archive', $archive)
                ->with('archive_relation', $archive_relation);
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    public function add_relation(Request $request, $slug_name)
    {
        $content_id = ContentHeader::getContentIdBySlug($slug_name);

        ArchiveRelation::create([
            'archive_id' => $request->archive_id,
            'content_id' => $content_id,
            'created_at' => date("Y-m-d H:i"),
            'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002' //for now
        ]);

        return redirect()->back()->with('success_message', 'Content has been added to archive');
    }

    public function delete_relation($id){
        ArchiveRelation::destroy($id);

        return redirect()->back()->with('success_message', "Content has been removed from archive");
    }

    public function add_archive(Request $request){
        $slug = Generator::getSlugName($request->archive_name, "archive");

        Archive::create([
            'slug_name' => $slug,
            'archive_name' => $request->archive_name,
            'archive_desc' => null,
            'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002', //for now
            'created_at' => date('Y-m-d H:i:s'),
            'updated_by' => null,
            'updated_at' => null
        ]);

        return redirect()->back()->with('success_message', "Archive has been created");
    }
}

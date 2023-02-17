<?php
//For subdomain deploy!!!
//namespace App\Http\Controllers\Mifik;
//use App\Http\Controllers\Controller;

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\content;
use App\Models\tag;
use App\Models\archieve;
use App\Models\task;
use App\Models\Setting;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = DB::table('content')
            //->whereRaw('DATE(content_date_start) = ?', date("Y-m-d")) //For now, just testing.
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(3)->get();

        $tag = Tag::orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')->get();

        //Set active nav
        session()->put('active_nav', 'homepage');

        return view ('homepage.index')
            ->with('event', $event)
            ->with('tag', $tag);
    }

    // ================================= MVC =================================

    public function update_mot(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'MOT_range' => $request->MOT_range,
            'updated_at' => date("Y-m-d h:i"),
        ]);

        return redirect()->back()->with('success_message', 'Chart range updated');
    }

    public function update_mol(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'MOL_range' => $request->MOL_range,
            'updated_at' => date("Y-m-d h:i"),
        ]);

        return redirect()->back()->with('success_message', 'Chart range updated');
    }

    public function update_ce(Request $request, $id)
    {
        Setting::where('id', $id)->update([
            'CE_range' => $request->CE_range,
            'updated_at' => date("Y-m-d h:i"),
        ]);

        return redirect()->back()->with('success_message', 'Chart range updated');
    }

    public function add_event(Request $request)
    {
        if($request->content_tag != null){
            //Initial variable
            $tag = [];
            $total_tag = count($request->content_tag);

            //Iterate all selected tag
            for($i=0; $i < $total_tag; $i++){
                array_push($tag, $request->content_tag[$i]);
            }

            //Clean the json from quotes mark
            $tag = str_replace('"{',"{", json_encode($tag));
            $tag = str_replace('}"',"}", $tag);
            $tag = stripslashes($tag);
        } else {
            $tag = null;
        }

        $result = Content::create([
            'id_user' => 1, //For now
            'content_title' => $request->content_title,
            'content_subtitle' => null, //For now
            'content_desc' => $request->content_desc,
            'content_attach' => null, //For now
            'content_tag' => $tag,
            'content_loc' => null, //For now
            'content_date_start' => date("Y-m-d H:i", strtotime($request->content_date_start."".$request->content_time_start)),
            'content_date_end' => date("Y-m-d H:i", strtotime($request->content_date_end."".$request->content_time_end)),
            'created_at' => date("Y-m-d H:i"),
            'updated_at' => date("Y-m-d H:i")
        ]);

        return redirect()->back()->with('success_message', 'Create content success');
    }

    // ================================= API =================================
    public function getAllContent()
    {
        $cnt = content::orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(15);
        //Need pagination?
        return response()->json($cnt);
    }
    
    public function getContent($id)
    {
        $cnt = content::where('id', $id)->get();
        
        return response()->json($cnt);
    }

    public function getAllSchedule($date)
    {
        $cnt = content::selectRaw('id, content_title, content_desc, content_loc, content_tag, content_date_start, content_date_end, 1 as data_from')
            ->whereRaw("date(`content_date_start`) = ?", $date)
            ->orderBy('content.content_date_start', 'DESC');
            
        $sch = task::selectRaw('id, task_title as content_title, task_desc as content_desc, null as content_loc, null as content_tag, task_date_start as content_date_start, task_date_end as content_date_end, 2 as data_from')
            ->where('id_user', 1)
            ->whereRaw("date(`task_date_start`) = ?", $date)
            ->orderBy('task.task_date_start', 'DESC')
            ->union($cnt)
            ->get();
            
        //Need pagination?
        return response()->json($sch);
    }
    
    public function getAllTag()
    { 
        $tag = tag::orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->get();
        
        return response()->json($tag);
    }
    
    public function getMyArchieve($id_user)
    {
        // $ar = archieve::where('id_user', $id_user)->get();
        // Old select raw
        // archieve::selectRaw("archieve.id, archieve.archieve_name,  CASE WHEN content.content_type = 'event' THEN COUNT(content.id) ELSE 0 END AS event, CASE WHEN content.content_type = 'task' THEN COUNT(content.id) ELSE 0 END AS task")
        $ar = archieve::selectRaw("archieve.id, archieve.archieve_name,  CASE WHEN archieve_relation.rel_type = 'content' THEN COUNT(content.id) ELSE 0 END AS event, CASE WHEN archieve_relation.rel_type = 'task' THEN COUNT(content.id) ELSE 0 END AS task")
            ->leftjoin('archieve_relation', 'archieve.id', '=', 'archieve_relation.archieve_id')
            ->leftjoin('content', 'content.id', '=', 'archieve_relation.content_id')
            // ->join('task', 'task.id', '=', 'archieve_relation.content_id')
            ->where('archieve.id_user', $id_user)
            ->groupBy('archieve.id')
            ->orderBy('archieve.created_at', 'DESC')->get();
        return response()->json($ar);
    }

    public function getMySchedule(Request $request, $id){
        $sch = content::selectRaw('content.id, content_title, content_subtitle, content_desc, content_attach, content_tag, content_loc, content_date_start, content_date_end, content.created_at, content.updated_at, archieve_relation.id as id_rel')
            ->join('archieve_relation', 'archieve_relation.content_id', '=', 'content.id')
            ->where('archieve_relation.archieve_id', $id)
            ->orderBy('archieve_relation.created_at', 'DESC')
            ->get();
        
        return response()->json($sch);
    }

    public function addContent(Request $request, $id_user)
    {
        $result = content::create([
            'id_user' => $id_user,
            'content_title' => $request->content_title,
            'content_subtitle' => $request->content_subtitle,
            'content_desc' => $request->content_desc,
            'content_attach' => $request->content_attach,
            'content_tag' => $request->content_tag,
            'content_loc' => $request->content_loc,
            'content_date_start' => $request->content_date_start,
            'content_date_end' => $request->content_date_end,
            'created_at' => date("Y-m-d h:i"),
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Content successfully added',
            'result' => $result,
        ]);
    }

    public function addArchive(Request $request, $id_user)
    {
        $result = archieve::create([
            'id_user' => $id_user,
            'archieve_name' => $request->archieve_name,
            'created_at' => date("Y-m-d h:i"),
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Archive successfully added',
            'result' => $result,
        ]);
    }

    public function editArchive(Request $request, $id)
    {
        //Validate name avaiability
        $check = archieve::where('archieve_name', $request->archieve_name)->where('id_user', $request->id_user)->get();

        if(count($check) == 0){
            $result = archieve::where('id', $id)->update([
                'archieve_name' => $request->archieve_name,
                'updated_at' => date("Y-m-d h:i")
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Archive successfully updated',
                'result' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Archive name must be unique',
                'result' => null,
            ]);
        }
    }
    
    public function deleteArchive(Request $request, $id)
    {
        $result = archieve::destroy($id);
        
        //Delete archive relation
        DB::table('archieve_relation')->where('archieve_id', $id)->where('user_id', $request->user_id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Archive successfully deleted',
            'result' => $result,
        ]);
    }
    
    public function getMyTask()
    {
        $tk = task::orderBy('task_date_end', 'DESC')->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate(15);
        //Need pagination?
        return response()->json($tk);
    }
    
    public function updateTask(Request $request, $id){
        $result = task::where('id', $id)->update([
            'task_title' => $request->task_title,
            'task_desc' => $request->task_desc,
            'task_date_start' => $request->task_date_start,
            'task_date_end' => $request->task_date_end,
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task successfully updated',
            'result' => $result,
        ]);
    }
    
    public function addTask(Request $request, $id_user)
    {
        $result = task::create([
            'id_user' => $id_user,
            'task_title' => $request->task_title,
            'task_desc' => $request->task_desc,
            'task_date_start' => $request->task_date_start,
            'task_date_end' => $request->task_date_end,
            'created_at' => date("Y-m-d h:i"),
            'updated_at' => date("Y-m-d h:i")
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Archive successfully added',
            'result' => $result,
        ]);
    }
}

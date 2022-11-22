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

class DashboardController extends Controller
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

        //Chart query
        $mostTag = Content::select('content_tag')
            ->whereNot('content_tag', null)
            ->get();

        $mostLoc = Content::select('content_loc')
            ->whereNot('content_loc', null)
            ->get();

        $setting = Setting::select('id', 'MOT_range', 'MOL_range', 'CE_range')
            ->where('id_user', 1)
            ->get();

        //Set active nav
        session()->put('active_nav', 'dashboard');

        foreach($setting as $set){
            $createdEvent = Content::selectRaw("MONTH(created_at) as 'month', COUNT(*) as total")
                ->where('created_at', '>=', date("Y-m-d", strtotime("-".$set->CE_range." months")))
                ->groupByRaw('MONTH(created_at)')
                ->get();
        }

        return view ('dashboard.index')
            ->with('event', $event)
            ->with('mostTag', $mostTag)
            ->with('mostLoc', $mostLoc)
            ->with('setting', $setting)
            ->with('createdEvent', $createdEvent);
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

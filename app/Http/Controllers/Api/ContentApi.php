<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Converter;
use App\Helpers\Generator;

use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\Task;

class ContentApi extends Controller
{
    public function getContentHeader()
    {
        try{
            $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->orderBy('contents_headers.created_at', 'DESC')
                ->paginate(12);

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Not Found',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Header Found',
                    'data' => $content
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContentBySlug($slug)
    {
        try{
            $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','content_attach','contents_headers.created_at')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->where('slug_name', $slug)
                ->get();

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Content Not Found'
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Header Found',
                    'data' => $content
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContentBySlugLike($slug, $order)
    {
        try{
            if($slug != "all"){
                $i = 1;
                $query = "";
                $filter_tag = explode(",", $slug);
                
                foreach($filter_tag as $ft){
                    $stmt = 'content_tag like '."'".'%"slug_name":"'.$ft.'"%'."'";

                    if($i != 1){
                        $query = substr_replace($query, " ".$stmt." OR", 0, 0);
                    } else {
                        $query = substr_replace($query, " ".$stmt, 0, 0);
                    }
                    $i++;
                }

                $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
                    ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                    ->orderBy('contents_headers.created_at', $order)
                    ->whereRaw($query)
                    ->paginate(12);

            } else {
                $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
                    ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                    ->orderBy('contents_headers.created_at', $order)
                    ->paginate(12);
            }

            if ($content->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Not Found',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Header Found',
                    'data' => $content
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllContentSchedule(Request $request, $date){
        try{
            $content = ContentHeader::selectRaw('slug_name, content_title, content_desc, content_loc, content_tag, content_date_start, content_date_end, 1 as data_from')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->whereRaw("date(`content_date_start`) = ?", $date)
                ->orderBy('content_date_start', 'DESC');
                
            $schedule = Task::selectRaw('slug_name, task_title as content_title, task_desc as content_desc, null as content_loc, null as content_tag, task_date_start as content_date_start, task_date_end as content_date_end, 2 as data_from')
                ->where('created_by', $request->user_id)
                ->whereRaw("date(`task_date_start`) = ?", $date)
                ->orderBy('tasks.task_date_start', 'DESC')
                ->union($content)
                ->get();

            if ($schedule->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Not Found',
                    'data' => $schedule
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content Found',
                    'data' => $schedule
                ], Response::HTTP_OK);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteContent(Request $request, $id){
        try{
            $content = ContentHeader::where('id', $id)->update([
                'deleted_at' => date("Y-m-d h:i:s"),
                'deleted_by' => $request->user_id,
            ]);

            if($content != 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Content deleted',
                    'data' => $content
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Content not found',
                    'data' => null
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroyContent($id){
        try{
            $content = ContentHeader::destroy($id);

            ContentDetail::where('content_id', $id)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Content permanentaly deleted',
                'data' => $content
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addContent(Request $request){
        try{
            //Inital variable 
            $draft = 0;
            $failed_attach = false;

            //Helpers
            $tag = Converter::getTag($request->content_tag);
            $fulldate_start = Converter::getFullDate($request->content_date_start, $request->content_time_start);
            $fulldate_end = Converter::getFullDate($request->content_date_end, $request->content_time_end);
            $slug = Generator::getSlugName($request->content_title, "content");

            // Attachment file upload
            $status = true;

            if(is_countable($request->attach_input)){
                $att_count = count($request->attach_input);
            
                for($i = 0; $i < $att_count; $i++){
                    if($request->hasFile('attach_input.'.$i)){
                        //validate image
                        $this->validate($request, [
                            'attach_input.'.$i     => 'required|max:10000',
                        ]);
            
                        //upload image
                        $att_file = $request->file('attach_input.'.$i);
                        $att_file->storeAs('public', $att_file->getClientOriginalName());

                        //get success message 
                        // ????
                        $status = true;
                    } else {
                        $status = false;
                    }
                }
            } else {
                $status = true;
            }

            // Content image file upload
            if($request->hasFile('content_image')){
                //validate image
                $this->validate($request, [
                    'content_image'    => 'required|max:5000',
                ]);

                //upload image
                $att_file = $request->file('content_image');
                $imageURL = $att_file->hashName();
                $att_file->storeAs('public', $imageURL);
            } else {
                $imageURL = null;
            }
        
            if(!$status){
                $draft = 1;
                $failed_attach = true;
            }

            $header = ContentHeader::create([
                'slug_name' => $slug, 
                'content_title' => $request->content_title,
                'content_desc' => $request->content_desc,
                'content_date_start' => $fulldate_start,
                'content_date_end' => $fulldate_end,
                'content_reminder' => $request->content_reminder,
                'content_image' => $imageURL,
                'is_draft' => $draft, 
                'created_at' => date("Y-m-d H:i"),
                'created_by' => $request->user_id, //for now
                'updated_at' => null,
                'updated_by' => null,
                'deleted_at' => null,
                'deleted_by' => null
            ]);

            if($tag || $request->has('content_attach')){
                function getFailedAttach($failed, $att_content){
                    if($failed){
                        return null;
                    } else {
                        return $att_content;
                    }
                }
                
                $detail = ContentDetail::create([
                    'content_id' => $header->id, //for now
                    'content_attach' => getFailedAttach($failed_attach, $request->content_attach), 
                    'content_tag' => $tag,
                    'content_loc' => null, //for now 
                    'created_by' => date("Y-m-d H:i"), 
                    'updated_at' => null
                ]);
            }

            $full_result = ([
                "header" => $header,
                "detail" => $detail
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Content created',
                'data' => $full_result
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

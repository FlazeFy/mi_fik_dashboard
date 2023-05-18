<?php

namespace App\Http\Controllers\Api\TrashApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Task;
use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\History;
use App\Models\ArchiveRelation;
use App\Models\ContentViewer;

class Commands extends Controller
{
    public function recover_content_api($slug, $type)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        if($type == 1){
            $type = "event";
            $id = Generator::getContentId($slug);
        } else if($type == 2){
            $type = "task";
            $id = Generator::getTaskId($slug);
        }

        if($slug != null && $type != null){
            $data = new Request();
            $obj = [
                'history_type' => $type,
                'history_body' => "Has recover this ".$type
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return response()->json([
                    'status' => 'failed',
                    'message' => $errors
                ], Response::HTTP_BAD_REQUEST);
            } else {
                if($type == "event"){
                    ContentHeader::where('id', $id)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
                    ]);
                } else if($type == "task"){
                    Task::where('id', $id)->update([
                        'deleted_at' => null,
                        'deleted_by' => null
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

                // return redirect()->back()->with('success_message', ucfirst($type)." successfully recover");
                return response()->json([
                    'status' => 'success',
                    'message' => ucfirst($type)." successfully recover"
                ], Response::HTTP_OK);
            }
        } else {
            // return redirect()->back()->with('failed_message', ucfirst($type)." recover is failed, the event doesn't exist anymore");
            return response()->json([
                'status' => 'failed',
                'message' => ucfirst($type)." recover is failed, the event doesn't exist anymore"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy_content_api(Request $request, $slug, $type)
    {
        // $user_id = Generator::getUserIdV2(session()->get('role_key'));
        $user_id = $request->user()->id;

        if($type == 1){
            $type = "event";
            $id = Generator::getContentId($slug);
            $owner = Generator::getContentOwner($slug);
        } else if($type == 2){
            $type = "task";
            $id = Generator::getTaskId($slug);
            $owner = Generator::getTaskOwner($slug);
        }

        if($slug != null && $type != null){
            $data = new Request();
            $obj = [
                'history_type' => $type,
                'history_body' => 'Has destroy a '.$type.' called "'.$request->content_title.'"'
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                // return redirect()->back()->with('failed_message', $errors);
                return response()->json([
                    'status' => 'failed',
                    'message' => $errors
                ], Response::HTTP_BAD_REQUEST);
            } else {
                if($type == "event"){
                    ContentHeader::destroy($id);
                    ContentDetail::where('content_id', $id)->delete();
                    ArchiveRelation::where('content_id', $id)->delete();
                    History::where('context_id', $id)->where('history_type', 'event')->delete();
                    ContentViewer::where('content_id', $id)->where('type_viewer', 0)->delete();
                } else if($type == "task"){
                    Task::destroy($id);
                    ArchiveRelation::where('content_id', $id)->delete();
                    History::where('context_id', $id)->where('history_type', 'task')->delete();
                }

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type,
                    'context_id' => null,
                    'history_body' => $data->history_body,
                    'history_send_to' => $owner,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);

                // return redirect()->back()->with('success_message', ucfirst($type)." successfully destroyed");
                return response()->json([
                    'status' => 'success',
                    'message' => ucfirst($type)." successfully destroyed"
                ], Response::HTTP_OK);
            }
        } else {
            // return redirect()->back()->with('failed_message', ucfirst($type)." destroy is failed, the event doesn't exist anymore");
            return response()->json([
                'status' => 'failed',
                'message' => ucfirst($type)." destroy is failed, the event doesn't exist anymore"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

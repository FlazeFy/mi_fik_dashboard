<?php


namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Info;
use App\Models\Menu;
use App\Models\Dictionary;
use App\Models\History;

class InfoController extends Controller
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

        if($role == 1){
            if($user_id != null){
                $type = ["Info"];

                $info = Info::getAllInfo(session()->get('selected_filter_info_type'));
                $dictionary = Dictionary::getDictionaryByType($type);
                $menu = Menu::getMenu();
                $dct = Dictionary::getDictionaryByType("Info");

                //Set active nav
                session()->put('active_nav', 'system');
                session()->put('active_subnav', 'info');

                return view ('system.info.index')
                    ->with('info', $info)
                    ->with('menu', $menu)
                    ->with('dct', $dct)
                    ->with('dictionary', $dictionary);
            } else {
                return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
            }
        } else {
            return view("errors.403");
        }
    }

    public function update_type(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateInfoType($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "info",
                'history_body' => "Has updated a info type"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Info::where('id', $id)->update([
                    'info_type' => $request->info_type,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $user_id
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success updated info type');   
            }
        }  
    }

    public function update_body(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateInfoBody($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "info",
                'history_body' => "Has updated a info body"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Info::where('id', $id)->update([
                    'info_body' => $request->info_body,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $user_id
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success updated info body');   
            }
        }  
    }

    public function update_active(Request $request, $id, $active)
    {
        if ($active != "activate" && $active != "deactivate") {
            return redirect()->back()->with('failed_message', 'invalid type');
        } else {
            $user_id = Generator::getUserIdV2(session()->get('role_key'));
            $data = new Request();
            $obj = [
                'history_type' => "info",
                'history_body' => "Has ".$active." an info"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                if($active == "activate"){
                    $res = 1;
                } else if($active == "deactivate"){
                    $res = 0;
                }

                Info::where('id', $id)->update([
                    'is_active' => $res,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $user_id
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success '.$active.' an info');   
            }
        }  
    }

    public function update_pagloc(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateInfoPageLoc($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "info",
                'history_body' => "Has updated a info page and location"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Info::where('id', $id)->update([
                    'info_page' => $request->info_page, 
                    'info_location' => $request->info_location, 
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $user_id
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success updated info page and location');   
            }
        }  
    }

    public function create(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateInfoCreate($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "info",
                'history_body' => "Has created an info"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Info::create([
                    'id' => Generator::getUUID(),
                    'info_type' => $request->info_type, 
                    'info_page' => $request->info_page, 
                    'info_location' => $request->info_location, 
                    'info_body' => $request->info_body,  
                    'is_active' => 1,  
                    'created_at' => date("Y-m-d H:i:s"), 
                    'created_by' => $user_id, 
                    'updated_at' => null, 
                    'updated_by' => null, 
                    'deleted_at' => null, 
                    'deleted_by' => null 
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success created an info');   
            }
        }  
    }

    public function delete($id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $data = new Request();
        $obj = [
            'history_type' => "info",
            'history_body' => "Has delete an info"
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            Info::where('id', $id)->update([
                'deleted_at' => date("Y-m-d H:i:s"),
                'deleted_by' => $user_id
            ]);

            History::create([
                'id' => Generator::getUUID(),
                'history_type' => $data->history_type, 
                'context_id' => null, 
                'history_body' => $data->history_body, 
                'history_send_to' => null,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            ]);
            
            return redirect()->back()->with('success_message', 'Success deleted a info');   
        }
        
    }

    public function filter_type(Request $request)
    {
        session()->put('selected_filter_info_type', $request->info_type);

        return redirect()->back()->with('success_mini_message', 'Info filtered');
    }
}

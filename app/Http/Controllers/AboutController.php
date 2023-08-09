<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Converter;

use App\Models\Menu;
use App\Models\Help;
use App\Models\History;

class AboutController extends Controller
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
            $menu = Menu::getMenu();
            $about = Help::getAboutApp();
            $helplist = Help::getHelpListNType();
            $ctc = Help::getAboutContact();
        
            if($role == 1){
                $history_about = History::getHistoryByType("about");
                $history_help = History::getHistoryByType("help");
                $history_contact = History::getHistoryByType("contact");
            }
            
            //Set active nav
            session()->put('active_nav', 'about');
            session()->forget('active_subnav');

            if($role == 1){
                return view ('about.index')
                    ->with('menu', $menu)
                    ->with('about', $about)
                    ->with('h_help', $history_help)
                    ->with('h_about', $history_about)
                    ->with('h_contact', $history_contact)
                    ->with('helplist', $helplist)
                    ->with('ctc',$ctc);      
            } else {
                return view ('about.index')
                    ->with('menu', $menu)
                    ->with('about', $about)
                    ->with('helplist', $helplist)
                    ->with('ctc',$ctc);   
            }   
        } else {
            return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
        }
    }

    
    public function edit_about_app(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateAboutApp($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "about",
                'history_body' => "has edited about app"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $help = Help::getAboutApp();

                if($help != null){
                    foreach($help as $hp){
                        $id = $hp->id;
                    }

                    Help::where('id', $id)->update([
                        'help_body' => $request->help_body,
                        'updated_at' => date("Y-m-d H:i"),
                    ]);
    
                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => strtolower($data->history_type), 
                        'context_id' => null, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);
                    
                    return redirect()->back()->with('success_message', Generator::getMessageTemplate("business_update", "About us", null)); 
                } else {
                    return redirect()->back()->with('failed_message', Generator::getMessageTemplate("custom",'The item does not exist anymore',null)); 
                }
            }
        }  
    }

    public function add_help_type(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateHelp($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "help",
                'history_body' => "Has created a new type"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $type = trim(strtolower($request->help_type));
                $checkType = Help::getAboutHelpType($type);

                if($checkType == false){
                    Help::create([
                        'id' => Generator::getUUID(),
                        'help_type' => $type,
                        'help_category' => null,
                        'help_body' => null,
                        'created_at' => date("Y-m-d H:i"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'updated_by' => null,
                        'deleted_at' => null,
                        'deleted_by' => null,
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
                    
                    return redirect()->back()->with('success_message', Generator::getMessageTemplate("business_create",'help category',$type));  
                } else {
                    return redirect()->back()->with('failed_message', Generator::getMessageTemplate("failed_exist",'help category',$type));  
                }    
            }
        }  
    }

    public function edit_help_body(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateBodyTypeEdit($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "help",
                'history_body' => "Has updated a ".$request->help_category."'s help body"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Help::where('id', $id)->update([
                    'help_body' => Converter::getCleanQuotes($request->help_body),
                    'updated_at' => date("Y-m-d H:i"),
                    'updated_by' => $user_id,
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => Converter::getCleanQuotes($data->history_type), 
                    'context_id' => null, 
                    'history_body' => Converter::getCleanQuotes($data->history_body), 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', Generator::getMessageTemplate("business_update",'help body',null));  
            }
        }  
    }

    public function edit_about_contact(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateAboutContact($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "contact",
                'history_body' => "Has updated contact"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $contact = Help::select('id','help_category')
                    ->where('help_type','contact')
                    ->get();

                foreach($contact as $ct){
                    $i = 0;
                    foreach($request as $rq){
                        if($ct->help_category == $request->keys()[$i]){
                            $val = $request->keys()[$i];
                            Help::where('id', $ct->id)->update([
                                'help_body' => Generator::getContactTemplate($request->keys()[$i]).$request->$val,
                                'updated_at' => date("Y-m-d H:i"),
                                'updated_by' => $user_id,
                            ]);
                            break;
                        }
                        $i++;
                    }
                }

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', Generator::getMessageTemplate("business_update",'contact',null));  
            }
        }  
    }

    public function add_help_cat(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateBodyTypeEdit($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "help",
                'history_body' => "Has created a help category called '".$request->help_category."'"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $type = Converter::getCleanQuotes(trim(strtolower($request->help_type)));

                $checkEmptyType = Help::getAboutHelpCategory($type);
                $category = Converter::getCleanQuotes($request->help_category);

                if($checkEmptyType != null){
                    Help::where('id', $checkEmptyType)->update([
                        'help_category' => $category,
                        'created_at' => date("Y-m-d H:i"),
                        'created_by' => $user_id,
                    ]);
                } else {
                    Help::create([
                        'id' => Generator::getUUID(),
                        'help_type' => $type,
                        'help_category' => $category,
                        'help_body' => null,
                        'created_at' => date("Y-m-d H:i"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'updated_by' => null,
                        'deleted_at' => null,
                        'deleted_by' => null,
                    ]);
                }

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', Generator::getMessageTemplate("business_create",'help category',$category));  
            }
        }  
    }

    public function delete_help_cat(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateBodyTypeEdit($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "help",
                'history_body' => "Has deleted a help category called '".$request->help_category."'"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Help::destroy($id);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', Generator::getMessageTemplate("business_delete",'help category',$request->help_category));  
            }
        }  
    }

    public function toogle_edit_app($ctx, $switch)
    {
        session()->put('toogle_edit_'.$ctx, $switch);

        if($switch == "true"){
            return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("custom","You're in edit mode",null));
        } else {
            return redirect()->back()->with('success_mini_message', Generator::getMessageTemplate("custom","You're in view mode",null));
        }
    }
}

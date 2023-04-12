<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Helpers\Generator;
use App\Helpers\Validation;

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
        if(session()->get('slug_key')){
            $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();
            $about = Help::getAboutApp();
            $helplist = Help::getHelpListNType();
            $history_about = History::getHistoryByType("about");
            $history_help = History::getHistoryByType("help");
            
            //Set active nav
            session()->put('active_nav', 'about');

            return view ('about.index')
                ->with('menu', $menu)
                ->with('about', $about)
                ->with('h_help', $history_help)
                ->with('h_about', $history_about)
                ->with('helplist', $helplist)
                ->with('greet',$greet);
                
        } else {
            return redirect()->route('landing')
                ->with('failed_message', 'Your session time is expired. Please login again!');
        }
    }

    
    public function edit_about_app(Request $request)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role')); 

        $validator = Validation::getValidateAboutApp($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "about",
                'history_body' => "Has edited about app"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $help = Help::getAboutApp();
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
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'About Apps updated');  
            }
        }  
    }

    public function sort_section(Request $request, $navigation)
    {
        $active = $request->section;
        $about_menu = json_decode($request->menu);

        $i = array_search($active, $about_menu);
        array_splice($about_menu, $i, 1);

        if($navigation == "up"){
            array_splice($about_menu, $i - 1, 0, $active);
        } else if($navigation == "down"){
            array_splice($about_menu, $i + 1, 0, $active);
        }

        session()->put('about_menu', $about_menu);

        return redirect()->back()->with('success_message', 'Section has sorted'); 
    }

    public function add_help_type(Request $request)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role')); 

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
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);
                    
                    return redirect()->back()->with('success_message', 'Success created new help category');  
                } else {
                    return redirect()->back()->with('failed_message', 'Failed to created new help category. The help type is already exist');  
                }    
            }
        }  
    }

    public function edit_help_body(Request $request, $id)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role')); 

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
                    'help_body' => $request->help_body,
                    'updated_at' => date("Y-m-d H:i"),
                    'updated_by' => $user_id,
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success updated help body from '.$request->help_category);  
            }
        }  
    }

    public function add_help_cat(Request $request)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role')); 

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
                $type = trim(strtolower($request->help_type));

                $checkEmptyType = Help::getAboutHelpCategory($type);

                if($checkEmptyType != null){
                    Help::where('id', $checkEmptyType)->update([
                        'help_category' => $request->help_category,
                        'created_at' => date("Y-m-d H:i"),
                        'created_by' => $user_id,
                    ]);
                } else {
                    Help::create([
                        'id' => Generator::getUUID(),
                        'help_type' => $type,
                        'help_category' => $request->help_category,
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
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success added a new help category called '.$request->help_category);  
            }
        }  
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

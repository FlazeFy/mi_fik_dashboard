<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Dictionary;
use App\Models\DictionaryType;
use App\Models\Menu;
use App\Models\Info;
use App\Models\History;

class DictionaryController extends Controller
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
                $greet = Generator::getGreeting(date('h'));
                $dictionary = Dictionary::getAllDictionary();
                $dictionaryType = DictionaryType::all();
                $info = Info::getAvailableInfo("system");
                $menu = Menu::getMenu();
                
                //Set active nav
                session()->put('active_nav', 'system');
                session()->put('active_subnav', 'dictionary');

                return view ('system.dictionary.index')
                    ->with('menu', $menu)
                    ->with('info', $info)
                    ->with('dictionary', $dictionary)
                    ->with('dictionaryType', $dictionaryType)
                    ->with('greet',$greet);
            } else {
                return redirect("/")->with('failed_message','Session lost, please sign in again');
            }
        } else {
            return redirect("/403");
        }
    }

    public function update_type(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateDictionaryType($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "dictionary",
                'history_body' => "Has updated a dictionary type"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Dictionary::where('id', $id)->update([
                    'dct_type' => $request->dct_type,
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
                
                return redirect()->back()->with('success_message', 'Success updated dictionary type');   
            }
        }  
    }

    public function create(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateDictionaryInfo($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "dictionary",
                'history_body' => "Has updated a dictionary info"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                $slug = Generator::getSlugName($request->dct_name, "dct");

                Dictionary::create([
                    'id' => Generator::getUUID(),
                    'slug_name' => $slug,
                    'dct_name' => $request->dct_name,
                    'dct_desc' => $request->dct_desc,
                    'dct_type' => $request->dct_type,
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
                
                return redirect()->back()->with('success_message', 'Success created new dictionary');   
            }
        }  
    }

    public function update_info(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateDictionaryInfo($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "dictionary",
                'history_body' => "Has updated a dictionary info"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Dictionary::where('id', $id)->update([
                    'dct_name' => $request->dct_name,
                    'dct_desc' => $request->dct_desc,
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
                
                return redirect()->back()->with('success_message', 'Success updated dictionary info');   
            }
        }  
    }

    public function delete(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $validator = Validation::getValidateDictionaryInfo($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "dictionary",
                'history_body' => "Has deleted a '".$request->dct_name."' dictionary"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Dictionary::where('id', $id)->update([
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
                
                return redirect()->back()->with('success_message', 'Success deleted a dictionary');   
            }
        }  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

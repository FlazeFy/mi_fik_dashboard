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
        $greet = Generator::getGreeting(date('h'));
        $dictionary = Dictionary::getAllDictionary();
        $dictionaryType = DictionaryType::all();
        $menu = Menu::getMenu();
        
        //Set active nav
        session()->put('active_nav', 'system');
        session()->put('active_subnav', 'dictionary');

        return view ('system.dictionary.index')
            ->with('menu', $menu)
            ->with('dictionary', $dictionary)
            ->with('dictionaryType', $dictionaryType)
            ->with('greet',$greet);
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
                'history_type' => "info",
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

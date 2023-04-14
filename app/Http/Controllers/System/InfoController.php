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
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role'));
        $type = ["Info"];

        $greet = Generator::getGreeting(date('h'));
        $info = Info::getAllInfo();
        $dictionary = Dictionary::getDictionaryByType($type);
        $menu = Menu::getMenu();

        //Set active nav
        session()->put('active_nav', 'system');
        session()->put('active_subnav', 'info');

        return view ('system.info.index')
            ->with('info', $info)
            ->with('menu', $menu)
            ->with('dictionary', $dictionary)
            ->with('greet',$greet);
    }

    public function update_type(Request $request, $id)
    {
        $user_id = Generator::getUserId(session()->get('slug_key'), session()->get('role')); 

        $validator = Validation::getValidateInfoType($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "info",
                'history_body' => "Has updated a info"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Info::where('id', $id)->update([
                    'info_type' => $request->info_type,
                    'updated_at' => date("Y-m-d h:i:s"),
                    'updated_by' => $user_id
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
                
                return redirect()->back()->with('success_message', 'Success updated info type');   
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

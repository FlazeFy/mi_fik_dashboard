<?php

namespace App\Http\Controllers\Social;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Menu;
use App\Models\Question;
use App\Models\History;

class FaqController extends Controller
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
            $greet = Generator::getGreeting(date('h'));
            $menu = Menu::getMenu();
            $history = History::getHistoryByType("faq");
            
            //Set active nav
            session()->put('active_nav', 'social');
            session()->put('active_subnav', 'faq');


            return view ('social.faq.index')
                ->with('menu', $menu)
                ->with('history', $history)
                ->with('greet',$greet);
        } else {
            return redirect("/")->with('failed_message','Session lost, try to sign in again');
        }
    }

    public function set_answer(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateAnswerFaq($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "faq",
                'history_body' => "Has answered a faq question"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                Question::where('id', $request->question_id)->update([
                    'question_answer' => $request->question_answer,
                    'updated_at' => date("Y-m-d H:i"),
                    'updated_by' => $user_id,
                ]);

                History::create([
                    'id' => Generator::getUUID(),
                    'history_type' => $data->history_type, 
                    'context_id' => $request->question_id, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', 'Success answered a faq question');  
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

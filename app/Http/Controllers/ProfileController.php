<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Converter;

use App\Models\Menu;
use App\Models\Admin;
use App\Models\User;
use App\Models\Task;
use App\Models\UserRequest;
use App\Models\Notification;
use App\Models\ContentHeader;
use App\Models\History;
use App\Models\Question;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));
        $greet = Generator::getGreeting(date('h'));
        $menu = Menu::getMenu();
        
        if(session()->get('role_key') == 0){
            $user = User::find($user_id);
            $totalEvent = ContentHeader::getCountEngPostEvent($user_id);
            $totalNotif = null;
            $totalAcc = null;
            $totalQue = null;
            $totalTask = Task::getCountEngTask($user_id);
        } else {
            $user = Admin::find($user_id);
            $totalEvent = ContentHeader::getCountEngPostEvent($user_id);
            $totalNotif = Notification::getCountEngPostNotif($user_id);
            $totalAcc = UserRequest::getCountEngAccReq($user_id);
            $totalQue = Question::getCountEngQuestionAnswer($user_id);
            $totalTask = null;
        }
        
        $faq = Question::getQuestionByUserId($user_id);
        $myreq = UserRequest::getRecentlyRequest($user_id);
        
        //Set active nav
        session()->put('active_nav', 'profile');
        session()->forget('active_subnav');

        return view ('profile.index')
            ->with('menu', $menu)
            ->with('user', $user)
            ->with('totalEvent', $totalEvent)
            ->with('totalNotif', $totalNotif)
            ->with('totalQue', $totalQue)
            ->with('totalAcc', $totalAcc)
            ->with('totalTask', $totalTask)
            ->with('faq', $faq)
            ->with('myreq', $myreq)
            ->with('greet',$greet);
    }

    public function edit_profile(Request $request)
    {
        $role_key = session()->get('role_key');
        $user_id = Generator::getUserIdV2($role_key); 

        if($role_key == 1){
            $role = "admin";
            $validator = Validation::getValidateEditProfile($request, $role);
        } else {
            $role = "user";
            $validator = Validation::getValidateEditProfile($request, $role);
        }

        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => $role,
                'history_body' => "has updated the profile"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                if($role_key == 1){
                    Admin::where('id', $user_id)->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone,
                        'password' => $request->password,
                        'updated_at' => date("Y-m-d H:i"),
                    ]);
                } else {
                    User::where('id', $user_id)->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'password' => $request->password,
                        'updated_at' => date("Y-m-d H:i"),
                        'updated_by' => $user_id,
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
                
                return redirect()->back()->with('success_message', 'Profile updated');  
            }
        } 
    }

    public function request_role(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $hsAdd = new Request();
        $hsRemove = new Request();

        $objAdd = [
            'history_type' => "user",
            'history_body' => "request to add some role"
        ];
        $objRemove = [
            'history_type' => "user",
            'history_body' => "request to remove some role"
        ];

        $hsAdd->merge($objAdd);
        $hsRemove->merge($objRemove);

        $validatorHistoryAdd = Validation::getValidateHistory($hsAdd);
        $validatorHistoryRemove = Validation::getValidateHistory($hsRemove);

        if ($validatorHistoryAdd->fails() || $validatorHistoryRemove->fails()) {
            $errors = $validatorHistoryAdd->messages()." | ".$validatorHistoryRemove->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $add = [];
            $remove = [];

            $countAll = count($request->req_type); 
            for($i = 0; $i < $countAll; $i++){
                if($request->req_type[$i] == "add"){
                    array_push($add, $request->user_role[$i]);
                } else if($request->req_type[$i] == "remove"){
                    array_push($remove, $request->user_role[$i]);
                }
            }

            $roleAdd = Converter::getTag($add);
            $roleRemove = Converter::getTag($remove);

            $checkAdd = json_decode($roleAdd, true);
            $checkRemove = json_decode($roleRemove, true);

            if($checkAdd !== null || $checkRemove !== null || json_last_error() === JSON_ERROR_NONE){
                if(count($add) > 0){
                    UserRequest::create([
                        'id' => Generator::getUUID(),
                        'tag_slug_name' => $checkAdd,
                        'request_type' => "add",
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'updated_by' => null,
                        'is_rejected' => null,
                        'rejected_at' => null,
                        'rejected_by' => null,
                        'is_accepted' => 0,
                        'accepted_at' => null,
                        'accepted_by' => null,
                    ]);
        
                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $hsAdd->history_type, 
                        'context_id' => null, 
                        'history_body' => $hsAdd->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);
                }
                if(count($remove) > 0){
                    UserRequest::create([
                        'id' => Generator::getUUID(),
                        'tag_slug_name' => $checkRemove,
                        'request_type' => "remove",
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id,
                        'updated_at' => null,
                        'updated_by' => null,
                        'is_rejected' => null,
                        'rejected_at' => null,
                        'rejected_by' => null,
                        'is_accepted' => 0,
                        'accepted_at' => null,
                        'accepted_by' => null,
                    ]);
        
                    History::create([
                        'id' => Generator::getUUID(),
                        'history_type' => $hsRemove->history_type, 
                        'context_id' => null, 
                        'history_body' => $hsRemove->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => $user_id
                    ]);
                }

                return redirect()->back()->with('success_message', "Request sended"); 
            } else {
                return redirect()->back()->with('failed_message', "Request failed to sended. Format not valid"); 
            }            
        }
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

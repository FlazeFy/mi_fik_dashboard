<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\Generator;
use App\Helpers\Validation;
use App\Helpers\Converter;

use App\Models\Menu;
use App\Models\User;
use App\Models\UserRequest;
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
        $user = User::find($user_id);
        $faq = Question::getQuestionByUserId($user_id);
        $myreq = UserRequest::getRecentlyRequest($user_id);
        
        //Set active nav
        session()->put('active_nav', 'profile');
        session()->forget('active_subnav');

        return view ('profile.index')
            ->with('menu', $menu)
            ->with('user', $user)
            ->with('faq', $faq)
            ->with('myreq', $myreq)
            ->with('greet',$greet);
    }

    public function edit_profile(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $validator = Validation::getValidateEditProfile($request);
        if ($validator->fails()) {
            $errors = $validator->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $data = new Request();
            $obj = [
                'history_type' => "user",
                'history_body' => "has updated the profile"
            ];
            $data->merge($obj);

            $validatorHistory = Validation::getValidateHistory($data);
            if ($validatorHistory->fails()) {
                $errors = $validatorHistory->messages();

                return redirect()->back()->with('failed_message', $errors);
            } else {
                User::where('id', $user_id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'password' => $request->password,
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
                
                return redirect()->back()->with('success_message', 'Profile updated');  
            }
        } 
    }

    public function request_add(Request $request)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $data = new Request();
        $obj = [
            'history_type' => "user",
            'history_body' => "request to add some role"
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            $role = Converter::getTag($request->user_role);
        
            $check = json_decode($role, true);

            if($check !== null || json_last_error() === JSON_ERROR_NONE){
                UserRequest::create([
                    'id' => Generator::getUUID(),
                    'tag_slug_name' => $check,
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
                    'history_type' => $data->history_type, 
                    'context_id' => null, 
                    'history_body' => $data->history_body, 
                    'history_send_to' => null,
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => $user_id
                ]);
                
                return redirect()->back()->with('success_message', "Request sended"); 
            } else {
                return redirect()->back()->with('success_message', "Failed to send request, tag list not valid"); 
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

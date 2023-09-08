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
use App\Models\Info;
use App\Models\User;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

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

        if($role == 1){
            if($user_id != null){
                $menu = Menu::getMenu();
                $history = History::getHistoryByType("faq");
                $info = Info::getAvailableInfo("social/faq");
                
                //Set active nav
                session()->put('active_nav', 'social');
                session()->put('active_subnav', 'FAQ');

                return view ('social.faq.index')
                    ->with('menu', $menu)
                    ->with('history', $history)
                    ->with('info',$info);
            } else {
                return redirect("/")->with('failed_message',Generator::getMessageTemplate("lost_session", null, null));
            }
        } else {
            return view("errors.403");
        }
    }

    public function set_answer(Request $request)
    {
        DB::beginTransaction();

        try{
            $user_id = Generator::getUserIdV2(session()->get('role_key'));
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $messaging = $factory->createMessaging(); 

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
                    DB::table("questions")
                        ->where('id', $request->question_id)->update([
                            'question_answer' => $request->question_answer,
                            'updated_at' => date("Y-m-d H:i"),
                            'updated_by' => $user_id,
                    ]);

                    $user = DB::table("users")
                        ->select("firebase_fcm_token")
                        ->where('username',$request->question_owner)
                        ->first();

                    $firebase_token = $user->firebase_fcm_token;
                    if($firebase_token){
                        $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                        if($validateRegister['valid'] != null){
                            $notif_body = $request->question_answer;
                            $notif_title = "Hello ".$request->question_owner.", your question got an answer";
                            $message = CloudMessage::withTarget('token', $firebase_token)
                                ->withNotification(
                                    FireNotif::create($notif_body)
                                    ->withTitle($notif_title)
                                    ->withBody($notif_body)
                                )
                                ->withData([
                                    'slug' => $request->question_id,
                                    'module' => 'faq'
                                ]);
                            $response = $messaging->send($message);
                        } else {
                            DB::table("users")->where('id', $request->question_owner)->update([
                                "firebase_fcm_token" => null
                            ]);
                        }
                    }

                    DB::table("histories")->insert([
                        'id' => Generator::getUUID(),
                        'history_type' => $data->history_type, 
                        'context_id' => $request->question_id, 
                        'history_body' => $data->history_body, 
                        'history_send_to' => null,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                    ]);
                    
                    DB::commit();
                    return redirect()->back()->with('success_message', 'Success answered a faq question');  
                }
            }  
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed_message', Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null));
        }
    }

    public function delete($id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key'));

        $data = new Request();
        $obj = [
            'history_type' => "info",
            'history_body' => "Has delete a question"
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            Question::where('id', $id)->update([
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
            
            return redirect()->back()->with('success_message', 'Success deleted a question');   
        }
        
    }
}

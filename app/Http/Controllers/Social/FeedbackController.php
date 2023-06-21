<?php

namespace App\Http\Controllers\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Helpers\Generator;
use App\Helpers\Validation;

use App\Models\Menu;
use App\Models\History;
use App\Models\Feedback;
use App\Models\Dictionary;

class FeedbackController extends Controller
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
            $suggestion = Feedback::getAllFeedbackSuggestion();
            $feedback = Feedback::getAllFeedback(50, session()->get('selected_filter_suggest'));
            $dct = Dictionary::getDictionaryByType("Feedback");
            
            //Set active nav
            session()->put('active_nav', 'social');
            session()->put('active_subnav', 'feedback');

            return view ('social.feedback.index')
                ->with('menu', $menu)
                ->with('dct', $dct)
                ->with('suggestion', $suggestion)
                ->with('feedback', $feedback)
                ->with('greet',$greet);
        } else {
            return redirect("/")->with('failed_message','Session lost, please sign in again');
        }
    }

    public function delete_feedback(Request $request, $id)
    {
        $user_id = Generator::getUserIdV2(session()->get('role_key')); 

        $data = new Request();
        $obj = [
            'history_type' => "feedback",
            'history_body' => "Has deleted a feedback"
        ];
        $data->merge($obj);

        $validatorHistory = Validation::getValidateHistory($data);
        if ($validatorHistory->fails()) {
            $errors = $validatorHistory->messages();

            return redirect()->back()->with('failed_message', $errors);
        } else {
            Feedback::where('id', $id)->update([
                'deleted_at' => date("Y-m-d H:i"),
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
            
            return redirect()->back()->with('success_message', 'Success deleted a feedback');  
        }
    }

    public function filter_suggest(Request $request)
    {
        session()->put('selected_filter_suggest', $request->feedback_suggest);

        return redirect()->back()->with('success_message', 'Suggestion filtered');
    }
}

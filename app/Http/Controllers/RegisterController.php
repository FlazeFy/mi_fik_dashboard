<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Helpers\Validation;

use App\Models\Help;
use App\Models\Info;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ctc = Help::getAboutContact();
        $about = Help::getAboutApp();
        $info = Info::getAvailableInfo("register");

        return view('register.index')
            ->with('ctc',$ctc)
            ->with('about',$about)
            ->with('info',$info);
    }
}

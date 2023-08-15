<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Help;

class ForgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ctc = Help::getAboutContact();
        
        return view('forget.index')
            ->with('ctc',$ctc);
    }
}

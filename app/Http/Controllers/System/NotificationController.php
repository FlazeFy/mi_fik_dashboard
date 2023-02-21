<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notification = Notification::select('*')
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        //Set active nav
        session()->put('active_nav', 'system');

        return view ('system.notification.index')
            ->with('notification', $notification);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Deck;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class DashboardController extends AdminController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDashboard(Request $request) {
        return view('admin.dashboard.view', ['page'=>'dashboard']);
    }

}

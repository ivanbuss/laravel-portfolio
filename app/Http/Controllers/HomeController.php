<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\UsersDeck;
use Illuminate\Http\Request;
use App\Deck;
use Illuminate\Support\Facades\Auth;
use App\Collection;


class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('home');
    }

    public function welcome(Request $request) {
        return view('welcome', [
            'user' => $request->user(),
            'decks_amount' => Deck::count(),
            'decks_collection_amount' => UsersDeck::where('in_collection', '>', 0)->count(),
            'recent_added' => Deck::orderBy('created_at', 'desc')->limit(4)->get(),
            'recent_collected' =>  UsersDeck::orderBy('created_at', 'desc')->limit(4)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Deck;
use App\Http\Requests;
use App\User;
use App\UsersDeck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersDeckController extends Controller
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

    protected function createUsersDeck(Deck $deck, User $user) {
        return UsersDeck::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
        ]);
    }

    protected function resolveViewModeTpl($viewMode) {
        if ($viewMode == 'list') {
            return 'collections.item_list';
        }
        return 'collections.item_tile';
    }

    /**
     * Get a validator for an incoming add deck to collection request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        return Validator::make($data, [
            'deck_id' => 'exists:decks,id',
        ]);
    }

    protected function updateCollectionOrder($type, Request $request) {
        $user_decks = UsersDeck::where('user_id', Auth::user()->id)
            ->whereIn('id', $request->get('order'))
            ->get();

        $min_weight = 0; $max_weight = 0; $counter = 0;
        foreach($request->get('weights') as $weight) {
            if ($weight > $max_weight) $max_weight = $weight;
            if ($weight < $min_weight) $min_weight = $weight;
            $counter++;
            if ($max_weight - $min_weight < $counter) $max_weight = $min_weight + $counter;
        }

        foreach($request->get('order') as $order_item) {
            $new_weights[$order_item] = $max_weight;
            $max_weight--;
        }

        foreach($user_decks as $user_deck) {
            switch ($type) {
                case 'collection':
                    $user_deck->weight_collection = $new_weights[$user_deck->id];
                    break;
                case 'wishlist':
                    $user_deck->weight_wishlist = $new_weights[$user_deck->id];
                    break;
                case 'tradelist':
                    $user_deck->weight_tradelist = $new_weights[$user_deck->id];
                    break;
            }
            $user_deck->save();
        }
        return $new_weights;
    }
}

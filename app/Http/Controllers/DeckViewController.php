<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Requests;
use App\Services\Tracker;
use App\User;
use App\UsersDeck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Elastic\DeckSeeker as ElasticDeckSeeker;

class DeckViewController extends Controller
{

    protected $tracker;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Tracker $tracker)
    {
        $this->tracker = $tracker;
        $this->middleware('auth');
    }

    public function getView(Deck $deck, Request $request) {
        return $this->showDeckDetails($deck, $request->user());
    }

    public function showDeckDetails(Deck $deck, User $user) {
        $user_deck = UsersDeck::where('deck_id', $deck->id)->where('user_id', $user->id)->first();
        $collection_items = $user_deck ? $user_deck->in_collection : 0;
        $wishlist_items = $user_deck ? $user_deck->in_wishlist : 0;
        $tradelist_items = $user_deck ? $user_deck->in_tradelist : 0;
        $in_collections = $deck->collections()->count();
        $in_wishlist = $deck->wishlists()->count();
        $in_tradelist = $deck->tradelists()->count();
        $gallery = $deck->gallery()->get();

        $deckSeeker = new ElasticDeckSeeker();
        $params = [
          'decks_skip' => 1,
          'decks_take' => 4,
          'search_string' => $deck->name,
        ];
        $recomandations = $deckSeeker->setParams($params)->get();

        $recent_changes = [
            'features' => $this->tracker->hasRecentTagsAdded($deck, $user, 'features'),
            'colors' => $this->tracker->hasRecentTagsAdded($deck, $user, 'colors'),
            'themes' => $this->tracker->hasRecentTagsAdded($deck, $user, 'themes'),
            'tags' => $this->tracker->hasRecentTagsAdded($deck, $user, 'tags'),
        ];

        return view('deck.view', [
            'rating' => $deck->getUserRating($user),
            'deck' => $deck,
            'in_collections' => $in_collections,
            'in_wishlist' => $in_wishlist,
            'in_tradelist' => $in_tradelist,
            'collection_items' => $collection_items,
            'wishlist_items' => $wishlist_items,
            'tradelist_items' => $tradelist_items,
            'gallery' => $gallery,
            'recent_changes' => $recent_changes,
            'notes' => $deck->getNotes($user),
            'recomandations'=>$recomandations,
        ]);
    }
    
}

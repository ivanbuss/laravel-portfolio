<?php

namespace App\Services;


use App\Deck;
use App\DeckLog;
use App\Term;
use App\User;
use Illuminate\Support\Facades\Auth;

class Tracker {

	public function saveDeckTermsLog(Deck $deck, $attribute, Term $term, User $user = null) {
        if (!$user) $user = Auth::user();
        return DeckLog::create([
            'deck_id' => $deck->id,
            'user_id' => $user->id,
            'attribute' => $attribute,
            'term_id' => $term->term_id,
            'action' => 'add_tag'
        ]);
    }

    public function hasRecentTagsAdded(Deck $deck, User $user, $attribute) {
        return DeckLog::where('deck_id', $deck->id)
            ->where('user_id', $user->id)
            ->where('attribute', $attribute)
            ->where('action', 'add_tag')
            ->count();
    }

    public function getRecentTagsAdded(Deck $deck, User $user, $attribute) {
        return DeckLog::where('deck_id', $deck->id)
            ->where('user_id', $user->id)
            ->where('attribute', $attribute)
            ->where('action', 'add_tag')
            ->get();
    }

}
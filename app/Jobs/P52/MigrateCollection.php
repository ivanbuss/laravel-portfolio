<?php

namespace App\Jobs\P52;

use Carbon\Carbon;
use App\Jobs\Job;
use App\UsersDeck;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Old\Collection as OldCollection;
use App\User;
use App\Deck;

class MigrateCollection extends Job implements ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    protected $oldUserDeck;

    /**
     * Create a new job instance.
     * MigrateCollection constructor.
     * @param $collection
     */
    public function __construct($collection) {
        $this->oldUserDeck = $collection;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $this->oldUserDeck;

        // Check if the collection exists
        $collection = \DB::table('users_decks')
            ->select()
            ->leftJoin('users', 'users_decks.user_id', '=', 'users.id')
            ->leftJoin('decks', 'users_decks.deck_id', '=', 'decks.id')
            ->where('users.uid', '=', $this->oldUserDeck->uid)
            ->where('decks.nid', '=', $this->oldUserDeck->deck_nid)
            ->groupBy('users_decks.id')
            ->first();
        $user = User::where('uid', '=', $this->oldUserDeck->uid)->first();
        $deck = Deck::where('nid', '=', $this->oldUserDeck->deck_nid)->first();
        if (!$collection && $user && $deck) {
            $now = Carbon::now()->timestamp;
            $userDeck = UsersDeck::create([
                'user_id' => $user->id,
                'deck_id' => $deck->id,
                'notes' => $this->oldUserDeck->description . ' ' . $this->oldUserDeck->summary,
                'in_collection' => $this->oldUserDeck->quantity,
                'in_wishlist' => $this->oldUserDeck->wishlist,
                'in_tradelist' => $this->oldUserDeck->tradelist,
                'added_at_collection' => $now,
                'added_at_wishlist' => $now,
                'added_at_tradelist' => $now,
            ]);
            // migrate rating
            $deck->rate($this->oldUserDeck->rating ? $this->oldUserDeck->rating : 0, $user);
        }
    }
}

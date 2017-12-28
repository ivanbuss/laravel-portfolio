<?php

namespace App;

use Ghanem\Rating\Contracts\Ratingable;
use Illuminate\Database\Eloquent\Model;
use App\Deck;
use App\User;
use Carbon\Carbon;
use App\Traits\RatingTrait as PortfolioRatingTrait;

class UsersDeck extends Model implements Ratingable {

    use PortfolioRatingTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_decks';

    protected $dates = ['added_at_collection', 'added_at_wishlist', 'added_at_tradelist'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'deck_id', 'notes', 'weight_collection', 'weight_wishlist', 'weight_tradelist',
        'in_collection', 'in_wishlist', 'in_tradelist'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Get the user record associated with the collection item.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the deck record associated with the collection item.
     */
    public function deck() {
        return $this->belongsTo('App\Deck');
    }

    public function addedAgo() {
        $create = new Carbon($this->created_at);
        $now = new Carbon();
        return $now->now()->diffForHumans($create, true);
    }

    public function addToCollection() {
        $this->in_collection = $this->in_collection + 1;
        $this->added_at_collection = Carbon::now();
        $this->save();
    }

    public function removeFromCollection() {
        if ($this->in_collection > 0) {
            $this->in_collection = $this->in_collection - 1;
            $this->save();
        }
    }

    public function addToWishlist() {
        $this->in_wishlist = $this->in_wishlist + 1;
        $this->added_at_wishlist = Carbon::now();
        $this->save();
    }

    public function removeFromWishlist() {
        if ($this->in_wishlist > 0) {
            $this->in_wishlist = $this->in_wishlist - 1;
            $this->save();
        }
    }

    public function addToTradelist() {
        $this->in_tradelist = $this->in_tradelist + 1;
        $this->added_at_tradelist = Carbon::now();
        $this->save();
    }

    public function removeFromTradelist() {
        if ($this->in_tradelist > 0) {
            $this->in_tradelist = $this->in_tradelist - 1;
            $this->save();
        }
    }

    public function updateNotes($notes) {
        $this->notes = $notes;
        $this->save();
    }

}

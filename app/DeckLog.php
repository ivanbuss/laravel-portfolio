<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Deck;
use App\User;
use Carbon\Carbon;


class DeckLog extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deck_changes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'deck_id', 'attribute', 'value', 'term_id', 'action'
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

    public function term() {
        return $this->hasOne('App\Term', 'term_id', 'term_id');
    }

}

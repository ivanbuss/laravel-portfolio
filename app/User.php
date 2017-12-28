<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Profile;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use DB;


/**
 * Class User
 * @package App
 */
class User extends Authenticatable {

    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'name', 'email', 'password', 'uid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the user record associated with the profile.
     */
    public function profile() {
        return $this->hasOne('App\Profile');
    }

    public function decks() {
        return $this->hasMany('App\Deck', 'user_id', 'id');
    }

    public function roles() {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function tagged() {
        return $this->hasMany('App\DeckLog', 'user_id', 'id')->where('action', 'add_tag')->groupBy('deck_id');
    }

    public function ratings() {
        return $this->hasMany('Ghanem\Rating\Models\Rating', 'author_id', 'id')
            ->where('author_type', 'App\User')->where('ratingable_type', 'App\Deck');
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $user = parent::create($attributes);
        // Create default profile on user creation
        Profile::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'rank' => Profile::RANK_UNKNOWN,
            'bio' => isset($attributes['bio']) ? $attributes['bio'] : NULL,
            'gender' => isset($attributes['gender']) ? $attributes['gender'] : NULL,
            'birthday' => isset($attributes['birthday']) ? $attributes['birthday'] : NULL,
            'points' => isset($attributes['points']) ? $attributes['points'] : NULL,
        ]);
        return $user;
    }

    /**
     * Get user collection list items.
     */
    public function collectionList() {
        return $this->hasMany('App\UsersDeck')->where('in_collection', '>', 0);
    }

    /**
     * Get user wish list items.
     */
    public function wishList() {
        return $this->hasMany('App\UsersDeck')->where('in_wishlist', '>', 0);
    }

    /**
     * Get user trade list items.
     */
    public function tradeList() {
        return $this->hasMany('App\UsersDeck')->where('in_tradelist', '>', 0);
    }


    /**
     * @param array $roleIds
     */
    public function updateRolesById(array $roleIds) {
        $this->detachRoles($this->roles()->get()->all());
        foreach ($roleIds as $id) {
            $this->attachRole(Role::findOrFail($id));
        }
    }


}

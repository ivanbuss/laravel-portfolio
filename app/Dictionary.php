<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Term;


class Dictionary extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dictionaries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function terms() {
        return $this->hasMany('App\Term', 'dictionary_id', 'id');
    }
}

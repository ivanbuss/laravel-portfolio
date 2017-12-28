<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'url', 'nid'
    ];

}

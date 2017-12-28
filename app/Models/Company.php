<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'url', 'nid'
    ];

}

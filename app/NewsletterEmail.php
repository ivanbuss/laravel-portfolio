<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class NewsletterEmail extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'newsletter_emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

}

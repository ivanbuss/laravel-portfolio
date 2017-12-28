<?php

namespace App;

use App\Services\ImageProcessor;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Review extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'deck_id', 'body', 'image_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Get the user record associated with the user.
     */
    public function author() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the deck record associated with the review item.
     */
    public function deck() {
        return $this->belongsTo('App\Deck');
    }

    /**
     * Get the file record associated with the image.
     */
    public function image() {
        return $this->hasOne('App\FileModel', 'id', 'image_id');
    }

    public function getImage($preset) {
        if ($this->image) {
            $imageProcessor = new ImageProcessor;
            $path = $imageProcessor->presets_public_directory;
            return $path . '/' . $preset . '/' . $this->image->name;
        }
    }

}

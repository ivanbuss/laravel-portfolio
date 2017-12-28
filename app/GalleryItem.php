<?php

namespace App;

use App\Services\ImageProcessor;
use Illuminate\Database\Eloquent\Model;
use App\Dictionary;


class GalleryItem extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gallery_items';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'deck_id', 'image_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Features attribute.
     */
    public function tags() {
        return $this->belongsToMany('App\Term', 'gallery_tags');
    }


    public function image() {
        return $this->hasOne('App\FileModel', 'id', 'image_id');
    }

    public function deck() {
        return $this->belongsTo('App\Deck', 'id', 'deck_id');
    }

    public function getImage($preset) {
        if ($this->image_id && $this->image) {
            $imageProcessor = new ImageProcessor;
            $path = $imageProcessor->presets_public_directory;
            return $path . '/' . $preset . '/' . $this->image->name;
        }
        return null;
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\ImageProcessor;

/**
 * Class Profile
 * @package App
 */
class Profile extends Model
{
    
    // Ranks
    const RANK_UNKNOWN = 0;
    const RANK_1 = 1;
    const RANK_2 = 2;

    const DEFAULT_BACKGROUND_IMG = 'default_profile_background.jpg';
    const DEFAULT_AVATAR_IMG = 'default_profile.gif';
    

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'avatar_img',
        'background_img',
        'bio', 
        'rank',
        'points',
        'gender',
        'birthday',
        'country'
    ];

    protected $dates = ['birthday'];

    /**
     * Get the user record associated with the user.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the file record associated with the avatar image.
     */
    public function avatarImg() {
        return $this->hasOne('App\FileModel', 'id', 'avatar_img');
    }

    /**
     * Get the file record associated with the background image.
     */
    public function backgroundImg() {
        return $this->hasOne('App\FileModel', 'id', 'background_img');
    }

    /**
     * Get profile avatar image.
     *
     * @param $preset
     * @return string
     */
    public function getAvatarImg($preset) {
        if ($this->avatarImg) {
            $imageProcessor = new ImageProcessor;
            $path = $imageProcessor->presets_public_directory;
            return $path . '/' . $preset . '/' . $this->avatarImg->name;
        } else return $this->getDefaultAvatarImg();
    }

    /**
     * Get profle background image.
     *
     * @param $preset
     * @return string
     */
    public function getBackgroundImg($preset) {
        if ($this->backgroundImg) {
            $imageProcessor = new ImageProcessor;
            $path = $imageProcessor->presets_public_directory;
            return $path . '/' . $preset . '/' . $this->backgroundImg->name;
        } else return $this->getDefaultBackgroundImg();
    }

    /**
     * Get default profile avatar image.
     *
     * @return string
     */
    protected function getDefaultAvatarImg() {
        $imageProcessor = new ImageProcessor;
        return $imageProcessor->default_images_path . '/' . self::DEFAULT_AVATAR_IMG;
    }

    /**
     * Get default profile background image.
     *
     * @return string
     */
    protected function getDefaultBackgroundImg() {
        $imageProcessor = new ImageProcessor;
        return $imageProcessor->default_images_path . '/' . self::DEFAULT_BACKGROUND_IMG;
    }

    public function userRank() {
        $rank = 'User';
        if ($this->user->hasRole(['contributor'])) {
            $rank = 'Contributor';
        }
        if ($this->user->hasRole(['admin'])) {
            $rank = 'Admin';
        }

        return $rank;
    }
}

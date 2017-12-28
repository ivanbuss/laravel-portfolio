<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 01.06.16
 * Time: 10:06
 */

namespace App\Traits;

use App\User;
use Ghanem\Rating\Models\Rating;
use Illuminate\Database\Eloquent\Model;
use Ghanem\Rating\Traits\Ratingable;
use Auth;
use DB;


trait RatingTrait {

    use Ratingable;

    /**
     * Get rating of the user
     * @param User|null $user
     * @return Model|null|static
     */
    public function getUserRating(User $user) {
        return Rating::query()
            ->where('ratingable_id', $this->id)
            ->where('ratingable_type', static::class)
            ->where('author_id', $user->id)
            ->where('author_type', User::class)
            ->first();
    }

    public function getRating() {
        $user = Auth::user();
        if ($user) {
            $rating= $this->getUserRating($user);
            if ($rating instanceof Rating) {
                return $rating->rating;
            }
        }
        $rating = Rating::query()
            ->where('ratingable_id', $this->id)
            ->where('ratingable_type', static::class)
            ->avg('rating');

        if (is_null($rating))  {
            $rating = 0;
        }
        return round($rating);
    }


    /**
     * Rate the ratingable model
     *
     * @param $rate
     * @param User|null $user
     * @return mixed|static
     */
    public function rate($rate, User $user) {
        // Check if the current user rating exists.
        if ($rating = $this->getUserRating($user)) {
            // Update existing rating
            return $this->updateRating($rating->id, ['rating' => $rate]);
        }
        else {
            // Create rating
            return (new Rating())->createRating($this, ['rating' => $rate], $user);
        }
    }
}
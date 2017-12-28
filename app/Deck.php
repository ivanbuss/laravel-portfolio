<?php

namespace App;

use App\Dictionary;
use App\Services\ImageProcessor;
use App\Services\Vocabulary;
use App\Term;
use Illuminate\Database\Eloquent\Model;
use Ghanem\Rating\Contracts\Ratingable;
use App\Traits\RatingTrait as PortfolioRatingTrait;
use Illuminate\Support\Facades\Auth;
use \DB;
use App\Services\Elastic;
use App\Elastic\Deck as ElasticDeck;

class Deck extends Model implements Ratingable {

    use PortfolioRatingTrait;

    const DEFAULT_FRONT_IMG = 'default-front.jpg';
    const DEFAULT_BACK_IMG = 'default-back.jpg';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'decks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'front_img_id', 'back_img_id', 'description', 'company_id', 'edition', 'collection_id', 'release_year',
        'prod_run', 'printer_id', 'artist_id', 'card_stock', 'finish', 'customization', 'features', 'colors', 'style',
        'themes', 'tags', 'nid', 'launch_date'
    ];

    protected $dates = ['launch_date'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected static $terms = [
        'edition', 'prod_run', 'card_stock', 'finish', 'court',
        'features', 'colors', 'style', 'themes', 'tags'
    ];

    protected $elasticDoc;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->elasticDoc = new ElasticDeck($this);
    }

    /**
     * Delete deck
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->features()->detach();
        $this->themes()->detach();
        $this->colors()->detach();
        $this->tags()->detach();
        $this->collections()->delete();
        $this->wishlists()->delete();
        $this->tradelists()->delete();

        // Delete document from elastic index
        $this->elasticDoc->delete();

        return parent::delete();

    }

    /**
     * Get the user record associated with the deck.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the file record associated with the deck front image.
     */
    public function frontimage() {
        return $this->hasOne('App\FileModel', 'id', 'front_img_id');
    }

    /**
     * Get the file record associated with the deck back image.
     */
    public function backimage() {
        return $this->hasOne('App\FileModel', 'id', 'back_img_id');
    }

    /**
     * Get the file record associated with the artist record.
     */
    public function artist() {
        return $this->hasOne('App\Models\Artist', 'id', 'artist_id');
    }

    /**
     * Get the file record associated with the brand record.
     */
    public function brand() {
        return $this->hasOne('App\Models\Brand', 'id', 'collection_id');
    }

    /**
     * Get the file record associated with the company record.
     */
    public function company() {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    /**
     * Get the file record associated with the manufacturer record.
     */
    public function manufacturer() {
        return $this->hasOne('App\Models\Manufacturer', 'id', 'printer_id');
    }

    /**
     * Features attribute.
     */
    public function features() {
        return $this->belongsToMany('App\Term', 'deck_features');
    }

    public function getFeaturelistAttribute() {
        $features = $this->features()->lists('terms.value')->toArray();
        return implode(', ', $features);
    }

    public function hasFeature($value) {
        foreach($this->features as $feature) {
            if ($feature->value == $value) return TRUE;
        }
        return FALSE;
    }

    /**
     * Themes attribute.
     */
    public function themes() {
        return $this->belongsToMany('App\Term', 'deck_themes');
    }

    public function getThemelistAttribute() {
        $themes = $this->themes()->lists('terms.value')->toArray();
        return implode(', ', $themes);
    }

    public function hasTheme($value) {
        foreach($this->themes as $theme) {
            if ($theme->value == $value) return TRUE;
        }
        return FALSE;
    }

    /**
     * Styles attribute
     */
    public function styles() {
        return $this->belongsToMany('App\Term', 'deck_style');
    }

    public function getStylelistAttribute() {
        $themes = $this->styles()->lists('terms.value')->toArray();
        return implode(', ', $themes);
    }

    public function hasStyle($value) {
        foreach($this->styles as $style) {
            if ($style->value == $value) return TRUE;
        }
        return FALSE;
    }

    /**
     * Stock attribute
     */
    public function stock() {
        return $this->belongsToMany('App\Term', 'deck_stock');
    }

    public function getStocklistAttribute() {
        $themes = $this->stock()->lists('terms.value')->toArray();
        return implode(', ', $themes);
    }

    public function hasStock($value) {
        foreach($this->stock as $singleStock) {
            if ($singleStock->value == $value) return TRUE;
        }
        return FALSE;
    }

    /**
     * Colors attribute.
     */
    public function colors() {
        return $this->belongsToMany('App\Term', 'deck_colors');
    }

    public function getColorlistAttribute() {
        $colors = $this->colors()->lists('terms.value')->toArray();
        return implode(', ', $colors);
    }

    public function hasColor($value) {
        foreach($this->colors as $color) {
            if ($color->value == $value) return TRUE;
        }
        return FALSE;
    }

    /**
     * Tags attribute.
     */
    public function tags() {
        return $this->belongsToMany('App\Term', 'deck_tags');
    }

    public function getTaglistAttribute() {
        $tags = $this->tags()->lists('terms.value')->toArray();
        return implode(', ', $tags);
    }

    public function hasTag($value) {
        foreach($this->tags as $tag) {
            if ($tag->value == $value) return TRUE;
        }
        return FALSE;
    }

    public function hasTagByName($name, $value) {
        switch ($name) {
            case 'features':
                return $this->hasFeature($value);
            case 'colors':
                return $this->hasColor($value);
            case 'themes':
                return $this->hasTheme($value);
            case 'tags':
                return $this->hasTag($value);
        }
        return FALSE;
    }

    public function collections() {
        return $this->hasMany('App\UsersDeck', 'deck_id', 'id')->where('in_collection', '>', 0);
    }

    public function wishlists() {
        return $this->hasMany('App\UsersDeck', 'deck_id', 'id')->where('in_wishlist', '>', 0);
    }

    public function tradelists() {
        return $this->hasMany('App\UsersDeck', 'deck_id', 'id')->where('in_tradelist', '>', 0);
    }

    public function gallery() {
        return $this->hasMany('App\GalleryItem', 'deck_id', 'id');
    }

    public function users_deck() {
        if (Auth::user()) {
            return $this->hasOne('App\UsersDeck', 'deck_id', 'id')->where('user_id', Auth::user()->id);
        } else {
            return $this->hasOne('App\UsersDeck', 'deck_id', 'id')->where('user_id', 0);
        }
    }

    public function reviews() {
        return $this->hasMany('App\Review', 'deck_id', 'id');
    }

    /**
     * Get list of term fields
     *
     * @return array
     */
    public static function getTermFields() {
        return static::$terms;
    }

    /**
     * Save the deck model
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = []) {
        $vocabulary = new Vocabulary();
        foreach($this->getAttributes() as $key=>$option) {
            if (array_search($key, self::getTermFields()) !== FALSE) {
                $term = $vocabulary->getTerm($key, $option);
                if ($term) $this->setAttribute($key, $term->value);
            }
        }
        $result =  parent::save($options);

        // Add deck to index
        $this->elasticDoc->index();

        return $result;
    }

    public function getFrontImg($preset) {
        if ($this->frontimage) {
            $imageProcessor = new ImageProcessor;
            $path = $imageProcessor->presets_public_directory;
            return $path . '/' . $preset . '/' . $this->frontimage->name;
        } else return $this->getDefaultFrontImg();
    }

    public function getBackImg($preset) {
        if ($this->backimage) {
            $imageProcessor = new ImageProcessor;
            $path = $imageProcessor->presets_public_directory;
            return $path . '/' . $preset . '/' . $this->backimage->name;
        } else return $this->getDefaultBackImg();
    }

    public function getDefaultFrontImg() {
        $imageProcessor = new ImageProcessor;
        return $imageProcessor->default_images_path . '/' . self::DEFAULT_FRONT_IMG;
    }

    public function getDefaultBackImg() {
        $imageProcessor = new ImageProcessor;
        return $imageProcessor->default_images_path . '/' . self::DEFAULT_BACK_IMG;
    }

    public function inCollection(User $user) {
        return $this->collections()->where('user_id', $user->id)->count();
    }

    public function inWishlist(User $user) {
        return $this->wishlists()->where('user_id', $user->id)->count();
    }

    public function inTradelist(User $user) {
        return $this->tradelists()->where('user_id', $user->id)->count();
    }

    public function getNotes(User $user) {
        if (Auth::user() == $user) {
            return $this->users_deck ? $this->users_deck->notes : null;
        } else {
            $user_deck = UsersDeck::where('deck_id', $this->id)->where('user_id', $user->id)->first();
            return $user_deck ? $user_deck->notes : null;
        }
    }

}

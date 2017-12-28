<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 26.06.16
 * Time: 13:24
 */

namespace App\Classes;

use Illuminate\Http\Request;
use App\Deck;
use DB;
use Auth;


class DeckSeeker {

    protected $order;
    protected $skip;
    protected $take;
    protected $colors;
    protected $style;
    protected $cardStock;
    protected $features;
    protected $searchString;
    protected $quantity;

    protected $query;

    
    public function __construct() {
        $this->take = 22;
    }

    /**
     * Set parameters to the object properties
     * @param array $params
     */
    public function setParams(array $params = array()) {
        $this->order = isset($params['decks_sort']) && $params['decks_sort'] ? $params['decks_sort'] : $this->order;
        $this->skip = isset($params['decks_skip']) && $params['decks_skip'] ? $params['decks_skip'] : null;
        $this->take = isset($params['decks_take']) && $params['decks_take'] ? $params['decks_take'] : $this->take;
        $this->colors = isset($params['colors']) && $params['colors'] ? $params['colors'] : array();
        $this->style = isset($params['styles']) && $params['styles'] ? $params['styles'] : array();
        $this->cardStock = isset($params['stocks']) && $params['stocks'] ? $params['stocks'] : array();
        $this->features = isset($params['tuck']) && $params['tuck'] ? $params['tuck'] : array();
        $this->searchString = isset($params['search_string']) && $params['search_string'] ? $params['search_string'] : array();
        $this->quantity['from'] = isset($params['quantity_from']) && $params['quantity_from'] ? $params['quantity_from'] : 0;
        $this->quantity['to'] = isset($params['quantity_to']) && $params['quantity_to'] ? $params['quantity_to'] : null;

        $this->prepareQuery();
        
        return $this->query;
    }

    /**
     * Get $_POST variables and set it as parameters to the object properties
     *
     * @param Request $request
     */
    public function setRequestParams(Request $request) {
        return $this->setParams($request->all());
    }

    /**
     * Prepare query
     */
    protected function prepareQuery() {
        $this->query = Auth::user() ? Deck::with('users_deck') : Deck::query();
        $this->setOrder();
        $this->skip();
        $this->take();
        $this->setColors();
        $this->setStyles();
        $this->setCardStock();
        $this->setFeatures();
        $this->setSearchString();
        $this->setQuantity();
    }

    /**
     * Set search string condition
     */
    protected function setSearchString() {
        if ($this->searchString) {
            $this->query->where("decks.name", "like", "%{$this->searchString}%");
        }
    }

    /**
     * Sets card stock
     */
    protected function setCardStock() {
        foreach ($this->cardStock as $card_stock_id => $card_stock) {
            $this->query->where('card_stock', '=', $card_stock);
        }
    }

    /**
     * Sets style condition
     */
    protected function setStyles() {
        foreach ($this->style as $style_id => $style) {
            $this->query->where('style', '=', $style);
        }
    }

    /**
     * Sets features condition
     */
    protected function setFeatures() {
        if ($this->features) {
            $this->query->leftjoin('deck_features', function ($join) {
                $join->on('deck_features.deck_id', '=', 'decks.id');
            });
            $this->query->whereIn('deck_features.term_id', array_keys($this->features));
        }
    }

    /**
     * Sets colors condition
     */
    protected function setColors() {
        if ($this->colors) {
            $this->query->leftjoin("deck_colors", function ($join) {
                $join->on('deck_colors.deck_id', '=', 'decks.id');
            });
            $this->query->whereIn('deck_colors.term_id', array_keys($this->colors));
        }
    }

    /**
     * Sets quantity
     */
    protected  function setQuantity() {
        if ($this->quantity['from'] && $this->quantity['to']) {
            $this->query->whereBetween('prod_run', array($this->quantity['from'], $this->quantity['to']));
        }
    }

    /**
     * Set limit
     */
    protected function take() {
        $this->query->take($this->take);
    }

    /**
     * Set offset
     */
    protected function skip() {
        if ($this->skip) {
            $this->query->skip($this->skip);
        }
    }

    /**
     * Set order
     */
    protected function setOrder() {
        switch ($this->order) {
            case $this->order === 'newest':
                $this->query->orderBy('created_at', 'desc');
                break;

            case $this->order === 'oldest':
                $this->query->orderBy('created_at', 'asc');
                break;

            case $this->order === 'alphabetical':
                $this->query->orderBy('name', 'asc');
                break;

            case $this->order === 'random':
                $this->query->orderBy(DB::raw('RAND()'));
                break;

            default:
                break;
        }
    }




}
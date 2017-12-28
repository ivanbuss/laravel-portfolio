<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 19.08.16
 * Time: 13:13
 */

namespace App\Elastic;

use App\Deck as Model;
use Illuminate\Http\Request;


class DeckSeeker {

    protected $sorting;
    protected $from;
    protected $size;
    protected $colors;
    protected $style;
    protected $cardStock;
    protected $features;
    protected $searchString;
    protected $quantity;

    protected $params;

    protected $result;

    /**
     * Get $_POST variables and set it as object properties
     *
     * @param Request $request
     * @return DeckSeeker
     */
    public function setRequestParams(Request $request) {
        return $this->setParams($request->all());
    }

    /**
     * Set parameters to the object properties
     * @param array $params
     * @return $this
     */
    public function setParams(array $params = array()) {
        // Search string
        $this->searchString = isset($params['search_string']) && $params['search_string'] ? $params['search_string'] : null;
        // Ordering and sorting
        $this->sorting = isset($params['decks_sort']) && $params['decks_sort'] ? $params['decks_sort'] : $this->sorting;
        $this->from = isset($params['decks_skip']) && $params['decks_skip'] ? $params['decks_skip'] : 0;
        $this->size = isset($params['decks_take']) && $params['decks_take'] ? $params['decks_take'] : 22;
        // Filters
        $this->colors = isset($params['colors']) && $params['colors'] ? $params['colors'] : null;
        $this->style = isset($params['styles']) && $params['styles'] ? $params['styles'] : null;
        $this->cardStock = isset($params['stocks']) && $params['stocks'] ? $params['stocks'] : null;
        $this->features = isset($params['tuck']) && $params['tuck'] ? $params['tuck'] : null;
        $this->quantity['from'] = isset($params['quantity_from']) && $params['quantity_from'] ? $params['quantity_from'] : 0;
        $this->quantity['to'] = isset($params['quantity_to']) && $params['quantity_to'] ? $params['quantity_to'] : null;

        $this->params = [
            "query" => [
                "bool" => [],
            ],
            "from" => $this->from,
            "size" => $this->size,
        ];
        $this->setSearching();
        $this->setFiltering();
        $this->sorting();
        $this->search();

        return $this;
    }

    protected function setSearching() {
        if ($this->searchString) {
            $this->params["query"]['bool']["must"] = ["match" => ["_all" => $this->searchString]];
        }
        else {
            $this->params["query"]['bool']["must"] = ["match_all" => []];
        }

        return $this;
    }

    protected function setFiltering() {
        if ($this->colors || $this->style || $this->cardStock || $this->features || $this->quantity) {
            $this->params["query"]['bool']['filter'] = [];
        }
        // Set filtering
        if ($this->colors) {
            $this->params["query"]['bool']['filter']['match']["colors"] = implode(",", $this->colors);
        }
        if ($this->style) {
            $this->params["query"]['bool']['filter']['match']["styles"] = implode($this->style);
        }
        if ($this->cardStock) {
            $this->params["query"]['bool']['filter']['match']["stocks"] = implode($this->cardStock);
        }
        if ($this->features) {
            $this->params["query"]['bool']['filter']['match']["features"] = implode($this->features);
        }
        // TODO: quantity field requires to be integer
//        if ($this->quantity) {
//            $this->query['bool']['filter']['match']["prod_run"] = implode($this->quantity);
//        }

    }

    protected function sorting() {
        switch ($this->sorting) {
            case $this->sorting === 'newest':
                $this->params['sort'] = [
                    "created_at" => ["order" =>"desc"],
                ];
                break;

            case $this->sorting === 'oldest':
                $this->params['sort'] = [
                    "created_at" => ["order" => "asc"],
                ];
                break;

            case $this->sorting === 'alphabetical':
                $this->params['sort'] = [
                    "name" => ["order" => "asc"],
                ];
                break;

            case $this->sorting === 'random':
                // TODO: implement random scoring
                break;

            default:
                // Default sorting is by score.
                break;
        }
    }

    protected function search() {
        $this->result = Deck::search($this->params);
        return $this;
    }

    public function loadDecks() {
        if ($this->result && $this->result["hits"]["hits"]) {
            $deckIds = [];

            foreach( $this->result["hits"]["hits"] as $hit) {
               $deckIds[] = $hit['_id'];
            }
            return Model::query()->whereIn('id', $deckIds)->get();

        } else {

            return array();
        }
    }

    public function get() {
        return $this->loadDecks();
    }

}
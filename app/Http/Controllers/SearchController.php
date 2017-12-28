<?php

namespace App\Http\Controllers;

use App\Services\Elastic;
use App\Services\Vocabulary;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Auth;
use App\Elastic\Deck as ElasticDeck;
use App\Elastic\DeckSeeker as ElasticDeckSeeker;

class SearchController extends Controller {


    protected $vocabulary;
    protected $deckSeeker;

    public function __construct(Elastic $elastic) {
        $this->elastic = $elastic;
        $this->vocabulary = new Vocabulary();
        //$this->deckSeeker = new DeckSeeker();
        $this->deckSeeker = new ElasticDeckSeeker();
    }

    /**
     * Discover page actions
     *
     * @param Request $request
     * @return mixed
     */
    public function discoverView(Request $request) {
        $params = [
            'order' => $request->order ? $request->order : null,
            'skip' => $request->skip ? $request->skip : null,
            'take' => $request->take ? $request->take : 22,
        ];
        $decks = $this->deckSeeker->setParams($params)->get();

        return view('discover.view', ['user' => Auth::user(), 'decks' => $decks]);
    }

    /**
     * Search page action
     *
     * @return mixed
     */
    public function searchView($params = array()) {
        $requesParams = request()->all();
        $params = array_merge($params, request()->all());

        return view('search.view', [
            'page_title' => 'Search deck',
            'active_tab' => (isset($requesParams['search_string']) && $requesParams['search_string'] ? 'discover' : 'search'),
            'params' => $params,
            'user' => Auth::user(), 
            'decks' => $this->deckSeeker->setParams($params)->get(),
            'colors' => $this->vocabulary->getTerms('colors', TRUE),
            'style' => $this->vocabulary->getTerms('styles', TRUE),
            'card_stock' => $this->vocabulary->getTerms('card_stock', TRUE),
            'features' => $this->vocabulary->getTerms('features', TRUE),
        ]);
    }

    /**
     * Search post action
     *
     * @param Request $request
     * @return mixed
     */
    public function searchPost(Request $request) {
        $post = $request->all();
        unset($post['_token']);
        return redirect()->action('SearchController@searchView', $post);
    }

    /**
     * Search post ajax action
     *
     * @param Request $request
     * @return mixed
     */
    public function searchPostAjax(Request $request) {

        $post = $request->all();
        $viewMode = isset($post['view']) &&  $post['view'] ? $post['view'] : 'tile';
        $decks = $this->deckSeeker->setRequestParams($request)->get();

        return view($this->resolveViewModeTpl($viewMode), ['user' => Auth::user(), 'decks' => $decks])->render();
    }


    protected function resolveViewModeTpl($viewMode) {
        if ($viewMode == 'list') {
            return 'deck.item_list';
        }

        return 'deck.item_tile';
    }

}

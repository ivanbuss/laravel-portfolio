<?php

namespace App\Http\Controllers;

use App\Dictionary;
use App\Http\Requests;
use App\Services\Vocabulary;
use App\Term;
use Illuminate\Http\Request;

class TermsAutocompleteController extends Controller
{

    protected $vocabulary;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Vocabulary $vocabulary)
    {
        $this->middleware('auth');
        $this->vocabulary = $vocabulary;
    }

    /**
     * Callback for an autocomplete feature in create deck page
     *
     * @param $dictionary_name
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function getSingleDeckTags($dictionary_name, Request $request) {
        $dictionary = $this->vocabulary->getDictionary($dictionary_name);
        $q = $request->get('term');
        if (!empty($q)) {
            $terms = $this->searchTerm($dictionary, $q)->toArray();
        } else {
            $terms = [];
        }


        return json_encode($terms);
    }

    /**
     * Callback for an autocomplete feature for multiple fields in create deck page
     *
     * @param $dictionary_name
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function getMultipleDeckTags($dictionary_name, Request $request) {
        $dictionary = $this->vocabulary->getDictionary($dictionary_name);
        $q = $request->get('term');
        $q_multiple = explode(',', $q);
        $last_term = trim(array_pop($q_multiple));
        if (!empty($last_term)) {
            $terms = $this->searchTerm($dictionary, $last_term)->toArray();
        } else {
            $terms = [];
        }

        return json_encode($terms);
    }

    protected function searchTerm(Dictionary $dictionary, $query, $limit = 5) {
        return Term::where('dictionary_id', $dictionary->id)
            ->where('value', 'LIKE', '%'.htmlspecialchars($query).'%')
            ->limit($limit)
            ->lists('value', 'term_id');
    }

}

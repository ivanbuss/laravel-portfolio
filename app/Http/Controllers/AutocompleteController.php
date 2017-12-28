<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Requests;
use App\Models\Artist;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Manufacturer;
use App\Services\Vocabulary;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
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
     * Callback for an autocomplete feature for related models in create deck page
     *
     * @param $dictionary_name
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function getSingleSuggestions($key, Request $request) {
        $q = $request->get('term'); $models = []; $suggestions = [];
        switch ($key) {
            case 'artist':
                $models = Artist::where('name', 'LIKE', '%'.$q.'%')->limit(5)->get();
                break;
            case 'collection':
                $models = Brand::where('name', 'LIKE', '%'.$q.'%')->limit(5)->get();
                break;
            case 'printer':
                $models = Manufacturer::where('name', 'LIKE', '%'.$q.'%')->limit(5)->get();
                break;
            case 'company':
                $models = Company::where('name', 'LIKE', '%'.$q.'%')->limit(5)->get();
                break;
            case 'deck':
                $models = Deck::where('name', 'LIKE', '%'.$q.'%')->limit(5)->get();
                break;
        }

        foreach($models as $model) {
            $suggestions[] = $model->name;
        }

        return json_encode($suggestions);
    }

}

<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Deck;

class ElasticAdminController extends Controller {

    public function elasticTest() {
//        Deck::deleteIndex();
//
//        Deck::createIndex($shards = null, $replicas = null);
//
//        Deck::putMapping($ignoreConflicts = true);

        Deck::addAllToIndex();
        
//        $deck = Deck::find(14);
//        $deck->color = 'black';
//        $deck->addToIndex();

        return 'Works';
    }

}

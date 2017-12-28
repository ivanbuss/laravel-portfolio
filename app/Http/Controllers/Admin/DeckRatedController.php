<?php

namespace App\Http\Controllers\Admin;

use App\Deck;
use App\Http\Requests;
use App\User;
use Carbon\Carbon;
use Ghanem\Rating\Models\Rating;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class DeckRatedController extends AdminController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin Deck upload page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDecksReport(Request $request) {
        return $this->showDeckRatedStatistic();
    }

    /**
     * Show the Deck upload report table.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDeckRatedStatistic() {
        $query = Rating::select('ratings.*', 'd.id as deck_id', 'd.name as deck_name', 'u.email as user_email')
            ->join('decks as d', 'ratings.ratingable_id', '=', 'd.id')
            ->join('users as u', 'ratings.author_id', '=', 'u.id')
            ->where('ratingable_type', 'App\Deck')
            ->where('author_type', 'App\User');
        $count = $query->count();
        return view('admin.deck_rated.report', ['page'=>'rated_decks', 'count'=>$count]);
    }

    public function getDeckReportTableData(Request $request) {
        $colums = [
            0 => ['name'=>'deck_id'],
            1 => ['name'=>'deck_name'],
            2 => ['name'=>'author_id'],
            3 => ['name'=>'user_email'],
            4 => ['name'=>'rating'],
            5 => ['name'=>'created_at']
        ];
        $count = 0;
        $orders = $request->get('order') ? $request->get('order') : [];

        $query = Rating::select('ratings.*', 'd.id as deck_id', 'd.name as deck_name', 'u.email as user_email')
            ->join('decks as d', 'ratings.ratingable_id', '=', 'd.id')
            ->join('users as u', 'ratings.author_id', '=', 'u.id')
            ->where('ratingable_type', 'App\Deck')
            ->where('author_type', 'App\User');

        $recordsTotal = $query->count();

        $search = $request->get('search') ? $request->get('search') : [];
        if ($search['value']) {
            $query->where(function($subquery) use ($search) {
                $subquery->where('d.name', 'LIKE', '%'.$search['value'].'%')
                    ->orWhere('u.email', 'LIKE', '%'.$search['value'].'%');
            });
        }
        $recordsFiltered = $query->count();

        $orders = $request->get('order') ? $request->get('order') : [];
        $this->dataTableSorting($query, $colums, $orders);

        $length = $request->get('length') ? $request->get('length') : 10;
        $start = $request->get('start') ? $request->get('start') : 0;
        $draw = $request->get('draw') ? $request->get('draw') : 1;

        $collective = $query->offset($start)->limit($length)->get();
        $items = [];
        foreach($collective as $item) {
            if (!$count) $count = $item->count;
            $items[] = [
                '<a href="'.action('Admin\DeckRatedController@getDeckReport', ['id'=>$item->deck_id]).'">'.$item->deck_id.'</a>',
                '<a href="'.action('Admin\DeckRatedController@getDeckReport', ['id'=>$item->deck_id]).'">'.$item->deck_name.'</a>',
                '<a href="'.action('ProfileController@show', $item->author_id).'">'.$item->author_id.'</a>',
                '<a href="'.action('ProfileController@show', $item->author_id).'">'.$item->user_email.'</a>',
                $item->rating,
                $item->created_at->format('jS M y')
            ];
        }

        return json_encode(['draw'=>$draw, 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$recordsFiltered, 'data'=>$items]);
    }

    /**
     * Show the admin User decks report page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDeckReport(Request $request, Deck $deck) {
        return view('admin.deck_rated.deck-report', ['page'=>'rated_decks', 'deck'=>$deck]);
    }

    public function postDeckReport(Request $request, Deck $deck) {
        $filter = $request->get('filter');
        $filter_value = $request->get('value');
        return $this->showDecksRatedStatistic($deck, $filter, $filter_value);
    }

    /**
     * Show the Deck rating report.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDecksRatedStatistic(Deck $deck, $filter, $value) {
        $countries = $deck->ratings()->join('profiles as p', 'p.user_id', '=', 'ratings.author_id')->whereNotNull('p.country')->distinct()->lists('p.country');

        $data = [];
        $data['labels']['age'] = ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'];
        $data['labels']['collection'] = ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'];
        $data['labels']['gender'] = ['Male', 'Female'];
        $data['labels']['location'] = [];
        foreach($countries as $country) {
            $data['labels']['location'][] = $country;
        }

        if ($filter == 'all') {
            $filters = ['age', 'collection', 'gender', 'location'];
        } else {
            $filters = [$filter];
        }

        foreach($filters as $filter_item) {
            if ($filter_item == 'gender') {
                $data['results'][$filter_item] = [
                    $deck->ratings()->join('profiles as p', 'p.user_id', '=', 'ratings.author_id')->where('p.gender', 'm'),
                    $deck->ratings()->join('profiles as p', 'p.user_id', '=', 'ratings.author_id')->where('p.gender', 'f'),
                ];
            } elseif ($filter_item == 'location') {
                $data['results'][$filter_item] = [];
                foreach($countries as $country) {
                    $data['results'][$filter_item][] = $deck->ratings()->join('profiles as p', 'p.user_id', '=', 'ratings.author_id')->where('p.country', $country);
                }
            } else {
                $data['results'][$filter_item] = [
                    $deck->ratings()->where('rating', 1),
                    $deck->ratings()->where('rating', 2),
                    $deck->ratings()->where('rating', 3),
                    $deck->ratings()->where('rating', 4),
                    $deck->ratings()->where('rating', 5),
                ];
            }

            if ($filter == 'age' && $value) {
                $args = explode(',', $value);
                foreach($data['results'][$filter_item] as $key=>$result) {
                    $result->join('profiles as p', 'p.user_id', '=', 'ratings.author_id');
                    if ($args[0]) {
                        $birthday = Carbon::now()->subYears($args[0]);
                        $data['results'][$filter_item][$key] = $result->where('p.birthday', '<=', $birthday);
                    }
                    if ($args[1]) {
                        $birthday = Carbon::now()->subYears($args[1]);
                        $data['results'][$filter_item][$key] = $result->where('p.birthday', '>=', $birthday);
                    }
                }
            } else if ($filter == 'collection' && $value) {
                $args = explode(',', $value);
                foreach($data['results'][$filter_item] as $key=>$result) {
                    if ($args[0]) $data['results'][$filter_item][$key] = $result->select('ratings.id')->having(DB::raw('(SELECT COUNT(*) FROM collections WHERE user_id = ratings.author_id)'), '>=', $args[0])->get();
                    if ($args[1]) $data['results'][$filter_item][$key] = $result->select('ratings.id')->having(DB::raw('(SELECT COUNT(*) FROM collections WHERE user_id = ratings.author_id)'), '<=', $args[1])->get();
                }
            } else if (($filter == 'gender' || $filter == 'location') && $value) {
                $arg = $value;
                foreach($data['results'][$filter_item] as $key=>$result) {
                    if ($arg) $data['results'][$filter_item][$key] = $result->where('rating', $arg);
                }
            }

            foreach($data['results'][$filter_item] as $key=>$result) {
                $data['results'][$filter_item][$key] = $result->count();
            }
        }
        return json_encode(['success'=>TRUE, 'data'=>$data]);
    }

    public function getTest() {
        $deck = Deck::find(2);
        /*
        $results = $deck->ratings()
            ->leftjoin('collections', 'collections.user_id', '=', 'ratings.author_id')
            ->having(DB::raw('COUNT(collections.id)'), '>=', 0)
            ->having(DB::raw('COUNT(collections.id)'), '<', 10)
            ->groupby('ratings.id')
            ->count();
        */
        $results = $deck->ratings()->select('ratings.id')
            ->having(DB::raw('(SELECT COUNT(*) FROM collections WHERE user_id = ratings.author_id)'), '>=', 0)
            ->having(DB::raw('(SELECT COUNT(*) FROM collections WHERE user_id = ratings.author_id)'), '<=', 6)
            ->get();
        p($results->count()); exit;
    }

}

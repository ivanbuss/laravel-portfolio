<?php

namespace App\Http\Controllers\Admin;



use App\DeckLog;
use Illuminate\Http\Request;
use App\Deck;
use Illuminate\Support\Facades\DB;

class DeckTaggedController extends AdminController
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
    public function getDeckReport(Request $request) {
        return $this->showDeckTaggedStatistic();
    }

    /**
     * Show the Deck upload report table.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDeckTaggedStatistic() {
        $query = DeckLog::orderBy('created_at', 'DESC');
        $count = $query->count();
        return view('admin.deck_tagged.report', ['page'=>'tagged_decks', 'count'=>$count]);
    }

    public function getDeckReportTableData(Request $request) {
        $colums = [
            0 => ['name'=>'deck_changes.deck_id'],
            1 => ['name'=>'decks.name'],
            2 => ['name'=>'user_id'],
            3 => ['name'=>'users.email'],
            4 => ['name'=>'deck_changes.attribute'],
            5 => ['name'=>'terms.value'],
            6 => ['name'=>'deck_changes.created_at'],
            7 => ['name'=>'tags_count']
        ];
        $count = 0;
        $orders = $request->get('order') ? $request->get('order') : [];

        $query = DeckLog::select('deck_changes.*', 'decks.id AS deck_id', 'decks.name', 'users.id', 'users.email', 'terms.value',
            DB::raw('(SELECT COUNT(*) FROM deck_changes WHERE user_id = users.id) AS tags_count'))
            ->where('action', 'add_tag')
            ->join('decks', 'deck_changes.deck_id', '=', 'decks.id')
            ->join('users', 'deck_changes.user_id', '=', 'users.id')
            ->join('terms', 'deck_changes.term_id', '=', 'terms.term_id');

        $recordsTotal = $query->count();

        $search = $request->get('search') ? $request->get('search') : [];
        if ($search['value']) {
            $query->where(function($subquery) use ($search) {
                $subquery->where('decks.name', 'LIKE', '%'.$search['value'].'%')
                    ->orWhere('email', 'LIKE', '%'.$search['value'].'%')
                    ->orWhere('deck_changes.attribute', 'LIKE', '%'.$search['value'].'%')
                    ->orWhere('terms.value', 'LIKE', '%'.$search['value'].'%');
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
                '<a href="'.action('Admin\DeckRatedController@getDeckReport', ['id'=>$item->deck_id]).'">'.$item->name.'</a>',
                '<a href="'.action('ProfileController@show', $item->user_id).'">'.$item->user_id.'</a>',
                '<a href="'.action('ProfileController@show', $item->user_id).'">'.$item->email.'</a>',
                $item->attribute,
                $item->value,
                $item->created_at->format('jS M y'),
                $item->tags_count
            ];
        }

        return json_encode(['draw'=>$draw, 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$recordsFiltered, 'data'=>$items]);
    }

}

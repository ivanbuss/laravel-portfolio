<?php

namespace App\Http\Controllers\Admin;

use App\Deck;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class DeckUploadController extends AdminController
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
        return $this->showDeckUploadStatistic();
    }

    /**
     * Show the Deck upload report table.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDeckUploadStatistic() {
        $query = Deck::with(['user' => function($q) {
            $q->with('decks');
        }])->orderBy('created_at', 'DESC');
        $count = $query->count();
        return view('admin.deck_upload.report', ['page'=>'uploaded_decks', 'count'=>$count]);
    }

    public function getDeckReportTableData(Request $request) {
        $colums = [
            0 => ['name'=>'decks.id'],
            1 => ['name'=>'decks.name'],
            2 => ['name'=>'user_id'],
            3 => ['name'=>'users.email'],
            4 => ['name'=>'decks.created_at'],
            5 => ['name'=>'deck_count']
        ];
        $count = 0;
        $orders = $request->get('order') ? $request->get('order') : [];

        $query = Deck::select(
            'decks.id', 'decks.name', 'decks.created_at', 'users.id AS user_id', 'users.email',
            DB::raw('(SELECT COUNT(*) FROM decks WHERE user_id = users.id) AS deck_count')
        )->join('users AS users', 'users.id', '=', 'decks.user_id');

        $recordsTotal = $query->count();

        $search = $request->get('search') ? $request->get('search') : [];
        if ($search['value']) {
            $query->where('decks.name', 'LIKE', '%'.$search['value'].'%');
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
                '<a href="'.action('DeckEditController@getEdit', $item->id).'">'.$item->id.'</a>',
                '<a href="'.action('DeckEditController@getEdit', $item->id).'">'.$item->name.'</a>',
                '<a href="'.action('Admin\DeckUploadController@getUserReport', ['id'=>$item->user->id]).'">'.$item->user->id.'</a>',
                '<a href="'.action('Admin\DeckUploadController@getUserReport', ['id'=>$item->user->id]).'">'.$item->user->email.'</a>',
                $item->created_at->format('jS M y'),
                $item->deck_count
            ];
        }

        return json_encode(['draw'=>$draw, 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$recordsFiltered, 'data'=>$items]);
    }

    /**
     * Show the admin User decks report page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserReport(Request $request, User $user) {
        return $this->showUserDecksStatistic($user);
    }

    /**
     * Show the User's decks report table.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUserDecksStatistic(User $user) {
        $query = $user->decks()->orderBy('created_at', 'DESC');
        $count = $query->count();
        return view('admin.deck_upload.user-report', ['page'=>'uploaded_decks', 'count'=>$count, 'user'=>$user]);
    }

    public function getUserDecksTableData(Request $request, User $user) {
        $colums = [
            0 => ['name'=>'decks.id'],
            1 => ['name'=>'decks.name'],
            2 => ['name'=>'user_id'],
            3 => ['name'=>'users.email'],
            4 => ['name'=>'decks.created_at'],
        ];

        $count = 0;
        $orders = $request->get('order') ? $request->get('order') : [];

        $query = Deck::select(
            'decks.id', 'decks.name', 'decks.created_at', 'users.id AS user_id', 'users.email'
        )->join('users AS users', 'users.id', '=', 'decks.user_id')
        ->where('users.id', $user->id);

        $recordsTotal = $query->count();

        $search = $request->get('search') ? $request->get('search') : [];
        if ($search['value']) {
            $query->where('decks.name', 'LIKE', '%'.$search['value'].'%');
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
                $item->id,
                $item->name,
                '<a href="'.action('Admin\DeckUploadController@getUserReport', ['id'=>$item->user->id]).'">'.$item->user->id.'</a>',
                '<a href="'.action('Admin\DeckUploadController@getUserReport', ['id'=>$item->user->id]).'">'.$item->user->email.'</a>',
                $item->created_at->format('jS M y'),
                $item->deck_count
            ];
        }
        return json_encode(['draw'=>$draw, 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$recordsFiltered, 'data'=>$items]);
    }

}

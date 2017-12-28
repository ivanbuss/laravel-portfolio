<?php

namespace App\Http\Controllers\Admin;

use App\Deck;
use App\Http\Requests;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class UsersController extends AdminController
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
     * Show the admin users report page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsersList(Request $request) {
        /*
        $users = User::select('users.*', 'profiles.birthday',
                DB::raw('(SELECT COUNT(*) FROM decks WHERE user_id = users.id) AS deck_count'),
                DB::raw('(SELECT COUNT(*) FROM deck_changes WHERE user_id = users.id AND action="add_tag") AS deck_tagged'),
                DB::raw('(SELECT COUNT(*) FROM ratings WHERE author_id = users.id) AS deck_ratings'),
                DB::raw('(SELECT COUNT(*) FROM collections WHERE user_id = users.id) AS colletion'),
                DB::raw('(SELECT COUNT(*) FROM wishlists WHERE user_id = users.id) AS wishlist'),
                DB::raw('(SELECT COUNT(*) FROM tradelists WHERE user_id = users.id) AS tradelist')
            )->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->get();
        foreach($users as $user) {
            if ($user->birthday) {
                $birthday = Carbon::createFromFormat('Y-m-d', $user->birthday);
                p(Carbon::now()->diffInYears($birthday)); exit;
            }

            //($user->birthday ? Carbon::now()->diffInYears($user->birthday) : null),
        }
        exit;
        */
        return $this->showUsersStatistic();
    }

    protected function showUsersStatistic() {
        $count = User::count();
        return view('admin.users.list', ['page'=>'users_list', 'count'=>$count]);
    }

    public function getUsersListTableData(Request $request) {
        $columns = [
            0 => ['name'=>'users.name'],
            1 => [],
            2 => ['name'=>'users.email'],
            3 => [],
            4 => ['name'=>'users.created_at'],
            5 => ['name'=>'birthday'],
            6 => [],
            7 => ['name'=>'deck_count'],
            8 => ['name'=>'deck_tagged'],
            9 => ['name'=>'deck_ratings'],
            10 => ['name'=>'colletion'],
            11 => ['name'=>'wishlist'],
            12 => ['name'=>'tradelist'],
        ];
        $count = 0;
        $orders = $request->get('order') ? $request->get('order') : [];

        $query = User::select('users.*', 'profiles.birthday',
            DB::raw('(SELECT COUNT(*) FROM decks WHERE user_id = users.id) AS deck_count'),
            DB::raw('(SELECT COUNT(*) FROM deck_changes WHERE user_id = users.id AND action="add_tag") AS deck_tagged'),
            DB::raw('(SELECT COUNT(*) FROM ratings WHERE author_id = users.id) AS deck_ratings'),
            DB::raw('(SELECT COUNT(*) FROM collections WHERE user_id = users.id) AS colletion'),
            DB::raw('(SELECT COUNT(*) FROM wishlists WHERE user_id = users.id) AS wishlist'),
            DB::raw('(SELECT COUNT(*) FROM tradelists WHERE user_id = users.id) AS tradelist')
        )->join('profiles', 'profiles.user_id', '=', 'users.id');

        $recordsTotal = $query->count();

        $search = $request->get('search') ? $request->get('search') : [];
        if ($search['value']) {
            $query->where(function($subquery) use ($search) {
                $subquery->where('users.email', 'LIKE', '%'.$search['value'].'%')
                    ->orWhere('users.name', 'LIKE', '%'.$search['value'].'%');
            });
        }
        $recordsFiltered = $query->count();

        $orders = $request->get('order') ? $request->get('order') : [];
        $this->dataTableSorting($query, $columns, $orders);

        $length = $request->get('length') ? $request->get('length') : 10;
        $start = $request->get('start') ? $request->get('start') : 0;
        $draw = $request->get('draw') ? $request->get('draw') : 1;

        $collective = $query->offset($start)->limit($length)->get();
        $items = [];
        foreach($collective as $item) {
            if (!$count) $count = $item->count;
            $roles = []; $birthday = null;
            foreach($item->roles as $role) {
                $roles[] = $role->display_name;
            }
            if ($item->birthday) {
                $birthday = Carbon::createFromFormat('Y-m-d', $item->birthday);
            }
            $items[] = [
                '<a href="'.action('ProfileController@getEdit', $item->id).'">'.$item->name.'</a>',
                implode(', ', $roles),
                '<a href="'.action('ProfileController@show', $item->id).'">'.$item->email.'</a>',
                null,
                $item->created_at->format('m/d/Y'),
                ($birthday ? Carbon::now()->diffInYears($birthday) : null),
                null,
                $item->deck_count,
                $item->deck_tagged,
                $item->deck_ratings,
                $item->colletion,
                $item->wishlist,
                $item->tradelist,
            ];
        }

        return json_encode(['draw'=>$draw, 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$recordsFiltered, 'data'=>$items]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Requests;
use App\User;
use App\Tradelist;
use App\UsersDeck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TradelistController extends UsersDeckController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('getUserList', 'postSearch', 'postReorder');
    }

    public function getList() {
        $authUser = Auth::user();
        if ($authUser) {
            return redirect()->action('TradelistController@getUserList', $authUser->id);
        }
    }

    public function getUserList(User $user) {
        $authUser = Auth::user();
        if ($authUser && $authUser->id == $user->id) {
            $items = UsersDeck::with('user', 'deck')
                ->where('user_id', Auth::user()->id)
                ->where('in_tradelist', '>', 0)
                ->orderBy('weight_tradelist', 'DESC')->orderBy('created_at', 'DESC')
                ->take(20)->get();
            return view('collections.tradelist', [
                'page_title' => "The wishlist of " . Auth::user()->name . ' user.',
                'user' => Auth::user(),
                'profile' => Auth::user()->profile,
                'items'=>$items,
                'count'=>count($items),
                'type'=>'tradelist',
                'sortable'=>TRUE,
            ]);
        }
        
        $items = UsersDeck::with('user', 'deck')
            ->where('user_id', $user->id)
            ->where('in_tradelist', '>', 0)
            ->orderBy('weight_tradelist', 'DESC')->orderBy('created_at', 'DESC')
            ->take(20)->get();
        return view('collections.tradelist', [
            'page_title' => "The tradelist of " . $user->name . ' user.',
            'user' => $user,
            'profile' => $user->profile,
            'items'=>$items,
            'count'=>count($items),
            'type'=>'tradelist',
            'sortable'=>FALSE,
        ]);
    }

    public function postAdd(Request $request) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return json_encode(['error'=>true, 'messages'=>$validator->getMessageBag()]);
        }

        $deck = Deck::find($request->deck_id);
        $users_deck = UsersDeck::where('user_id', Auth::user()->id)->where('deck_id', $request->deck_id)->first();
        if ($users_deck) {
            $users_deck->addToTradelist();
        } else {
            $users_deck = $this->createUsersDeck($deck, Auth::user());
            $users_deck->addToTradelist();
        }

        return json_encode([
            'success'=>TRUE,
            'action'=>'add',
            'type'=>'tradelist',
            'collection_item'=>$users_deck->id,
            'collection_count'=>$users_deck->in_tradelist,
            'deck_id'=>$deck->id
        ]);
    }

    public function postRemove(Request $request) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return json_encode(['error'=>true, 'messages'=>$validator->getMessageBag()]);
        }

        $deck = Deck::find($request->deck_id);
        $users_deck = UsersDeck::where('user_id', Auth::user()->id)->where('deck_id', $request->deck_id)->first();
        if ($users_deck) {
            $users_deck->removeFromTradelist();
        }

        return json_encode([
            'success'=>TRUE,
            'action'=>'add',
            'type'=>'tradelist',
            'collection_item'=>$users_deck ? $users_deck->id : 0,
            'collection_count'=>$users_deck ? $users_deck->in_tradelist : 0,
            'deck_id'=>$deck->id
        ]);
    }

    public function postSearch(User $user, Request $request) {
        $viewMode = $request->has('view') ? $request->get('view') : 'list';

        $sort_type = $request->get('sort');
        $query = UsersDeck::with('deck')
            ->join('decks', 'users_decks.deck_id', '=', 'decks.id')
            ->where('users_decks.user_id', $user->id)
            ->where('in_tradelist', '>', 0)
            ->take($request->get('decks_take'))
            ->skip($request->get('decks_skip'));

        switch ($sort_type) {
            case 'newest':
                $query->orderBy('users_decks.created_at', 'desc');
                break;
            case 'custom':
                $query->orderBy('weight_collection', 'desc')->orderBy('users_decks.created_at', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy('decks.name', 'ASC');
                break;
            case 'random':
                $query->orderBy(DB::raw('RAND()'));
                break;
            default:
                $query->orderBy('weight_collection', 'desc')->orderBy('users_decks.created_at', 'desc');
                break;
        }
        $items = $query->get();

        return view($this->resolveViewModeTpl($viewMode), ['user' => $user, 'items' => $items, 'type'=>'tradelist'])->render();
    }

    public function postReorder(Request $request) {
        $new_weights = $this->updateCollectionOrder('tradelist', $request);
        return json_encode(['success'=>TRUE, 'new_weights'=>$new_weights]);
    }

}

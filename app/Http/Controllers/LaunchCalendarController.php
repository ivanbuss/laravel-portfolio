<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 31.05.16
 * Time: 10:22
 */

namespace App\Http\Controllers;

use App\Deck;
use App\Review;
use App\User;
use App\UsersDeck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\ImageProcessor;


class LaunchCalendarController extends Controller {

    protected $imageProcessor;

    public function __construct(ImageProcessor $imageProcessor) {
        $this->middleware('auth');
        $this->imageProcessor = $imageProcessor;
    }

    public function getCalendar(Request $request) {
        $decks = Deck::where('launch_date', '>', Carbon::now())->get();
        $user = $request->user();

        return view('calendar.list', ['decks'=>$decks, 'user'=>$user]);
    }

    public function getDeckView(Deck $deck, Request $request) {
        $user = $request->user();
        $user_deck = UsersDeck::where('deck_id', $deck->id)->where('user_id', $user->id)->first();
        $wishlist_items = $user_deck ? $user_deck->in_wishlist : 0;

        $timer = $deck->launch_date->diff(Carbon::now());
        $days = ($timer->d >= 10) ? $timer->d : '0'.$timer->d;
        $hours = ($timer->h >= 10) ? $timer->h : '0'.$timer->h;
        $mins = ($timer->i >= 10) ? $timer->i : '0'.$timer->i;

        $reviews = Review::where('deck_id', $deck->id)->get();

        return view('calendar.deck_item', [
            'page_title' => 'Launch Calendar',
            'deck'=>$deck, 'user'=>$user, 'days'=>$days, 'hours'=>$hours, 'mins'=>$mins,
            'wishlist_items'=>$wishlist_items,
            'reviews'=>$reviews,
        ]);
    }

    /**
     * The application review add page action.
     *
     * @param Deck $deck
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReviewAdd(Deck $deck, Request $request) {
        return $this->showReviewForm($deck);
    }

    /**
     * Review add form
     *
     * @param Deck $deck
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function showReviewForm(Deck $deck) {
        return view('calendar.review_form', [
            'deck'=>$deck,
        ]);
    }

    /**
     *
     *
     * @param Deck $deck
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function postReviewAdd(Deck $deck, Request $request) {
        $user = $request->user();
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $this->saveReview($request, $deck, $user);
        return redirect()->action('LaunchCalendarController@getDeckView', $deck->id)
            ->with(['message'=>'Review has been created']);
    }

    protected function saveReview(Request $request, Deck $deck, User $user) {
        $review = Review::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'body' => $request->get('body'),
        ]);
        $reviewImage = $this->imageProcessor->uploadReviewImage($request, $review, 'image');
        if ($reviewImage) {
            $review->image_id = $reviewImage->id;
            $review->save();
        }
        return $review;
    }

    /**
     * Get a validator for an incoming add review request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'image' => 'image',
            'body' => 'required',
        ]);
    }

}

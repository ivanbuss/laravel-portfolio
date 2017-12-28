<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 31.05.16
 * Time: 10:22
 */

namespace App\Http\Controllers;

use App\Deck;
use Ghanem\Rating\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class RatingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }
    
    public function rate(Request $request) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return json_encode(['error'=>true, 'messages'=>$validator->getMessageBag()]);
        }
        $post = $request->all();
        
        // Get ratingable entity
        $type = $post['type'];
        switch ($type) {
            case 'Deck':
                $model = Deck::find($post['id']);
                break;
        }
        if ($model) {
            $model->rate($post['rating'], $request->user());
            return json_encode([
                'success'=>TRUE,
            ]);
        } else {
            return json_encode([
                'success'=>FALSE,
            ]);
        }
    }

    /**
     * Get a validator for an incoming add deck to collection request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        return Validator::make($data, [
            'deck_id' => 'exists:decks,id',
        ]);
    }

    
}

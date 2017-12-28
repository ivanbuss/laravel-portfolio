<?php

namespace App\Http\Controllers;

use App\GalleryItem;
use App\Http\Requests;
use App\Services\ImageProcessor;
use App\Services\Vocabulary;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Auth;

class DeckGalleryController extends Controller
{

    protected $imageProcessor;
    protected $vocabulary;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageProcessor $imageProcessor, Vocabulary $vocabulary)
    {
        $this->middleware('auth');
        $this->imageProcessor = $imageProcessor;
        $this->vocabulary = $vocabulary;
    }

    /**
     * Show the application Gallery add page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGalleryForm(Request $request) {
        if ($request->ajax()) return json_encode(['success'=>TRUE, 'form'=>$this->showGalleyForm()->render()]);
            else return $this->showGalleyForm();
    }

    /**
     * Show the Gallery add form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showGalleyForm() {
        return view('deck.gallery-form');
    }

    /**
     * Save a new deck action
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(Request $request) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            if ($request->ajax()) {
                return json_encode(['success'=>FALSE, 'messages'=>$validator->getMessageBag()]);
            } else {
                $this->throwValidationException(
                    $request, $validator
                );
            }
        }

        $gallery_item = $this->create($request->all(), Auth::user());
        if ($gallery_item) {
            $file = $this->imageProcessor->uploadGalleryImage($request, $gallery_item, 'gallery_image');
            $gallery_item->image_id = $file->id;
            $gallery_item->save();
            if ($request->ajax()) {
                return json_encode(['success'=>TRUE, 'item_id'=>$gallery_item->id, 'gallery_image'=>$gallery_item->getImage('700x500')]);
            }
            $request->session()->flash('status', 'Gallery has been created');
            return redirect()->action('DeckGalleryController@getGalleryForm');
        }
        if ($request->ajax()) {
            return json_encode(['success'=>FALSE, 'messages'=>'error']);
        }
        return redirect()->action('DeckGalleryController@getGalleryForm');
    }

    /**
     * Get a validator for an incoming create gallery request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        $dictionary = $this->vocabulary->getDictionary('gallery_tags');
        return Validator::make($data, [
            'gallery_image' => 'required|image',
            'gallery_tag' => 'exists:terms,value,dictionary_id,'.$dictionary->id,
            'gallery_tag_box' => 'exists:terms,value,dictionary_id,'.$dictionary->id,
            'gallery_tag_card' => 'exists:terms,value,dictionary_id,'.$dictionary->id,
            'gallery_tag_card_type' => 'exists:terms,value,dictionary_id,'.$dictionary->id,
            'gallery_tag_card_court' => 'exists:terms,value,dictionary_id,'.$dictionary->id,
            'gallery_tag_card_pip' => 'exists:terms,value,dictionary_id,'.$dictionary->id,
            'gallery_tag_card_joker' => 'exists:terms,value,dictionary_id,'.$dictionary->id,
        ]);
    }

    /**
     * Create a new gallery item instance after a validation.
     *
     * @param array $data
     * @return GalleryItem
     */
    protected function create(array $data, User $user) {
        $gallery_item = GalleryItem::create([
            'user_id' => $user->id,
        ]);
        $tags = [];
        if ($data['gallery_tag']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag'])->term_id;
        if ($data['gallery_tag_box']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag_box'])->term_id;
        if ($data['gallery_tag_box_side']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag_box_side'])->term_id;
        if ($data['gallery_tag_card']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag_card'])->term_id;
        if ($data['gallery_tag_card_type']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag_card_type'])->term_id;
        if ($data['gallery_tag_card_court']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag_card_court'])->term_id;
        if ($data['gallery_tag_card_pip']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag_card_pip'])->term_id;
        if ($data['gallery_tag_card_joker']) $tags[] = $this->vocabulary->getTerm('gallery_tags', $data['gallery_tag_card_joker'])->term_id;
        if ($tags) $gallery_item->tags()->attach($tags);

        return $gallery_item;
    }

}

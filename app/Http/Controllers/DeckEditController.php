<?php

namespace App\Http\Controllers;

use App\Deck;
use App\GalleryItem;
use App\Http\Requests;
use App\Models\Artist;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Manufacturer;
use App\Services\ImageProcessor;
use App\Services\Vocabulary;
use App\Term;
use App\User;
use App\UsersDeck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Validator;
use App\Services\Tracker;
use Illuminate\Support\Facades\Auth;
use Entrust;

class DeckEditController extends Controller {

    protected $terms;
    protected $imageProcessor;
    protected $vocabulary;
    protected $tracker;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageProcessor $imageProcessor, Vocabulary $vocabulary, Tracker $tracker) {
        $this->middleware('auth');
        $this->imageProcessor = $imageProcessor;
        $this->vocabulary = $vocabulary;
        $this->tracker = $tracker;
    }

    /**
     * Show the application Deck add page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdd(Request $request) {
        $gallery_items = [];
        if ($request->old('gallery')) {
            foreach($request->old('gallery') as $gallery_id) {
                if ($gallery_id) {
                    $gallery_items[] = GalleryItem::find($gallery_id);
                }
            }
        }
        return $this->showDeckAddForm($gallery_items);
    }

    /**
     * Show the Desck add form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDeckAddForm($gallery_items) {
        return view('deck.add', ['gallery_items'=>$gallery_items]);
    }

    /**
     * Save a new deck action
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(Request $request) {
        return $this->createDeck($request);
    }

    /**
     * Show the application Deck edit page.
     *
     * @param Deck $deck
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getEdit(Deck $deck, Request $request) {
        $gallery_items = [];
        if ($request->old('gallery')) {
            foreach($request->old('gallery') as $gallery_id) {
                if ($gallery_id) {
                    $gallery_items[] = GalleryItem::find($gallery_id);
                }
            }
        } else {
            $gallery_items = $deck->gallery;
        }
        return $this->showDeckEditForm($deck, $gallery_items);
    }

    /**
     * Show the Desck add form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDeckEditForm(Deck $deck, $gallery_items) {
        return view('deck.edit', ['deck'=>$deck, 'gallery_items'=>$gallery_items]);
    }

    /**
     * Update a new deck action
     *
     * @param Deck $deck
     * @param Request $request
     */
    public function postUpdate(Deck $deck, Request $request) {
        return $this->updateDeck($deck, $request);
    }

    /**
     * Ajax handler for update deck tags
     * @param Deck $deck
     * @param Request $request
     * @return json
     */
    public function postTermsEdit(Deck $deck, Request $request) {
        $validator = $this->editTermsValidator($request->all());
        if ($validator->fails()) {
            return json_encode(['success' => false, 'messages' => $validator->getMessageBag()]);
        }
        $name = $request->get('name'); $value = $request->get('value');
        return $this->editTerms($deck, $name, $value);
    }

    /**
     * Ajax handler for reset deck tags
     * @param Deck $deck
     * @param Request $request
     * @return json
     */
    public function postTermsReset(Deck $deck, Request $request) {
        $attribute = $request->has('attribute') ? $request->get('attribute') : null;
        $user = $request->user();
        return $this->cancelTerms($deck, $user, $attribute);
    }

    /**
     * Handle a creation deck request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    protected function createDeck(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
              $request, $validator
            );
        }
        $deck = $this->create($request->all(), Auth::user());
        $front_img = $this->imageProcessor->uploadDeckImage($request, $deck, 'front_img');
        if ($front_img) $deck->front_img_id = $front_img->id;
        $back_img = $this->imageProcessor->uploadDeckImage($request, $deck, 'back_img');
        if ($back_img) $deck->back_img_id = $back_img->id;
        $deck->save();

        $request->session()->flash('status', 'Deck has been created');
        return redirect()->action('DeckViewController@getView', $deck->id);
    }


    protected function updateDeck(Deck $deck, Request $request) {
        $validator = $this->updateValidator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $deck = $this->update($deck, $request->all(), Auth::user());
        $front_img = $this->imageProcessor->uploadDeckImage($request, $deck, 'front_img');
        if ($front_img) $deck->front_img_id = $front_img->id;
        $back_img = $this->imageProcessor->uploadDeckImage($request, $deck, 'back_img');
        if ($back_img) $deck->back_img_id = $back_img->id;
        $deck->save();

        $request->session()->flash('status', 'Deck has been updated');
        return redirect()->action('DeckViewController@getView', $deck->id);
    }

    /**
     * Handle for updation deck tags request for the application.
     *
     * @param Request $request
     * @param $field
     * @return json
     */
    public function postFileUpload(Request $request, $field) {
        if ($request->ajax()) {
            $file_name = $this->imageProcessor->uploadTempImage($request, $field);
            return json_encode(['field'=>$field, 'file'=>$file_name]);
        }
    }

    public function postNotes(Deck $deck, Request $request) {
        $validator = $this->notesValidator($request->all());

        if ($validator->fails()) {
            return json_encode(['success'=>FALSE, 'messages'=>$validator->getMessageBag()]);
        }
        return $this->updateNotes($deck, $request->user(), $request->get('notes'));
    }

    protected function editTerms(Deck $deck, $name, $value) {
        $tags = explode(',', $value); $tags_terms = []; $hasChanges = FALSE;

        foreach($tags as $tag) {
            if (!empty(trim($tag))) {
                $term = $this->vocabulary->getTerm($name, trim($tag));
                if ($term && !$deck->hasTagByName($name, $term->value)) {
                    $tags_terms[] = $term->term_id;
                    $terms[] = $term;
                    $hasChanges = TRUE;
                }
            }
        }

        $tags_line = '';
        switch ($name) {
            case 'stock':
                $deck->stock()->attach($tags_terms);
                $tags_line = $deck->stocklist;
                break;
            case 'style':
                $deck->styles()->attach($tags_terms);
                $tags_line = $deck->stylelist;
                break;
            case 'features':
                $deck->features()->attach($tags_terms);
                $tags_line = $deck->featurelist;
                break;
            case 'colors':
                $deck->colors()->attach($tags_terms);
                $tags_line = $deck->colorlist;
                break;
            case 'themes':
                $deck->themes()->attach($tags_terms);
                $tags_line = $deck->themelist;
                break;
            case 'tags':
                $deck->tags()->attach($tags_terms);
                $tags_line = $deck->taglist;
                break;
        }

        foreach($terms as $term) {
            $this->tracker->saveDeckTermsLog($deck, $name, $term);
        }

        if ($hasChanges) {
            $tags_line .= ' <a class="cancel-tags" rel="'.$name.'" role="button" href="'.action('DeckEditController@postTermsReset', ['id'=>$deck->id]).'">(Cancel)</a>';
        }

        return json_encode(['success'=>TRUE, 'tag_name'=>$name, 'value'=>$tags_line, 'hasChanges'=>$hasChanges]);
    }

    protected function cancelTerms(Deck $deck, User $user, $attribute) {
        $recent_actions = $this->tracker->getRecentTagsAdded($deck, $user, $attribute); $ids = [];
        foreach($recent_actions as $action) {
            $ids[] = $action->term_id;
        }

        switch ($attribute) {
            case 'features':
                $deck->features()->detach($ids);
                $tags_line = $deck->featurelist;
                break;
            case 'colors':
                $deck->colors()->detach($ids);
                $tags_line = $deck->colorlist;
                break;
            case 'themes':
                $deck->themes()->detach($ids);
                $tags_line = $deck->themelist;
                break;
            case 'tags':
                $deck->tags()->detach($ids);
                $tags_line = $deck->taglist;
                break;
        }

        foreach($recent_actions as $action) {
            $action->delete();
        }

        return json_encode(['success'=>TRUE, 'tag_name'=>$attribute, 'value'=>$tags_line]);
    }

    protected function updateNotes(Deck $deck, User $user, $notes) {
        $users_deck = UsersDeck::where('user_id', $user->id)->where('deck_id', $deck->id)->first();
        if (!$users_deck) $users_deck = $this->createUsersDeck($deck, $user);
        $users_deck->updateNotes($notes);
        return json_encode(['success'=>TRUE, 'notes'=>'test']);
    }

    /**
     * Get a validator for an incoming create deck request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'front_img' => 'image|max:5120',
            'back_img' => 'image|max:5120',
            'description' => '',
            'company' => 'max:255',
            'edition' => 'max:255',
            'collection' => 'max:255',
            'release_year' => 'date_format:Y',
            'prod_run' => 'max:255',
            'printer' => 'max:255',
            'artist' => 'max:255',
            'card_stock' => 'max:255',
            'finish' => 'max:255',
            'court' => 'max:255',
            'features' => 'max:255',
            'colors' => 'max:255',
            'style' => 'max:255',
            'themes' => 'max:255',
            'tags' => 'max:255',
        ]);
    }

    /**
     * Get a validator for an incoming update deck request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function updateValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'front_img' => 'image|max:5120',
            'back_img' => 'image|max:5120',
            'description' => '',
            'company' => 'max:255',
            'edition' => 'max:255',
            'collection' => 'max:255',
            'release_year' => 'date_format:Y',
            'prod_run' => 'max:255',
            'printer' => 'max:255',
            'artist' => 'max:255',
            'card_stock' => 'max:255',
            'finish' => 'max:255',
            'court' => 'max:255',
            'features' => 'max:255',
            'colors' => 'max:255',
            'style' => 'max:255',
            'themes' => 'max:255',
            'tags' => 'max:255',
        ]);
    }

    public function notesValidator(array $data) {
        return Validator::make($data, [
            'notes' => 'string|max:1000',
        ]);
    }

    /**
     * Edit deck's terms request validatior
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function editTermsValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|in:features,colors,themes,tags',
            'value' => 'required|max:255',
        ]);
    }

    /**
     * Create a new deck instance after a validation.
     *
     * @param array $data
     * @return Deck
     */
    protected function create(array $data, User $user) {
        $artist = Artist::where('name', $data['artist'])->first();
        if (!$artist) $artist = Artist::create(['name'=>$data['artist']]);
        $brand = Brand::where('name', $data['collection'])->first();
        if (!$brand) $brand = Brand::create(['name'=>$data['collection']]);
        $company = Company::where('name', $data['company'])->first();
        if (!$company) $company = Company::create(['name'=>$data['company']]);
        $manufacturer = Manufacturer::where('name', $data['printer'])->first();
        if (!$manufacturer) $manufacturer = Manufacturer::create(['name'=>$data['printer']]);

        $deck = Deck::create([
            'name' => $data['name'],
            'user_id' => $user->id,
            'front_img_id' => '',
            'back_img_id' => '',
            'description' => $data['description'],
            'company_id' => $company ? $company->id : null,
            'edition' => $data['edition'],
            'collection_id' => $brand ? $brand->id : null,
            'release_year' => $data['release_year'],
            'prod_run' => $data['prod_run'],
            'printer_id' => $manufacturer ? $manufacturer->id : null,
            'artist_id' => $artist ? $artist->id : null,
            // 'card_stock' => $data['card_stock'],
            'finish' => $data['finish'],
            'customization' => $data['court'],
            // 'style' => $data['style'],
        ]);

        $this->afterUpdate($deck, $data, $user);

        return $deck;
    }

    protected function update(Deck $deck, array $data, User $user) {
        $artist = Artist::where('name', $data['artist'])->first();
        if (!$artist) $artist = Artist::create(['name'=>$data['artist']]);
        $brand = Brand::where('name', $data['collection'])->first();
        if (!$brand) $brand = Brand::create(['name'=>$data['collection']]);
        $company = Company::where('name', $data['company'])->first();
        if (!$company) $company = Company::create(['name'=>$data['company']]);
        $manufacturer = Manufacturer::where('name', $data['printer'])->first();
        if (!$manufacturer) $manufacturer = Manufacturer::create(['name'=>$data['printer']]);
        /*
        $launch_date = null;
        if ($data['launch_date']) {
            $launch_date = Carbon::createFromFormat('m/d/Y h:s A', $data['launch_date']);
        }
        */
        $deck->name = $data['name'];
        $deck->description = $data['description'];
        $deck->company_id = $company ? $company->id : null;
        $deck->edition = $data['edition'];
        $deck->collection_id = $brand ? $brand->id : null;
        $deck->release_year = $data['release_year'];
        $deck->prod_run = $data['prod_run'];
        $deck->printer_id = $manufacturer ? $manufacturer->id : null;
        $deck->artist_id = $artist ? $artist->id : null;
        // $deck->card_stock = $data['card_stock'];
        $deck->finish = $data['finish'];
        $deck->customization = $data['court'];
        // $deck->style = $data['style'];
        $deck->save();

        $this->afterUpdate($deck, $data, $user);

        return $deck;
    }

    protected function afterUpdate(Deck $deck, array $data, User $user) {
        $cardStock = explode(',', $data['card_stock']); $cardStockTerms = [];
        $features = explode(',', $data['features']); $featureTerms = [];
        $themes = explode(',', $data['themes']); $themeTerms = [];
        $colors = explode(',', $data['colors']); $colorTerms = [];
        $tags = explode(',', $data['tags']); $tagTerms = [];
        $styles = explode(',', $data['style']); $styleTerms = [];
        foreach($cardStock as $singleStock) {
            $term = $this->vocabulary->getTerm('card_stock', trim($singleStock));
            if ($term) $cardStockTerms[] = $term->term_id;
        }
        foreach($features as $feature) {
            $term = $this->vocabulary->getTerm('features', trim($feature));
            if ($term) $featureTerms[] = $term->term_id;
        }
        foreach($themes as $theme) {
            $term = $this->vocabulary->getTerm('themes', trim($theme));
            if ($term) $themeTerms[] = $term->term_id;
        }
        foreach($colors as $color) {
            $term = $this->vocabulary->getTerm('colors', trim($color));
            if ($term) $colorTerms[] = $term->term_id;
        }
        foreach($styles as $style) {
            $term = $this->vocabulary->getTerm('styles', trim($style));
            if ($term) $styleTerms[] = $term->term_id;
        }
        foreach($tags as $tag) {
            $term = $this->vocabulary->getTerm('tags', trim($tag));
            if ($term) $tagTerms[] = $term->term_id;
        }
        $deck->features()->detach();
        $deck->themes()->detach();
        $deck->colors()->detach();
        $deck->styles()->detach();
        $deck->stock()->detach();
        $deck->tags()->detach();

        $deck->features()->attach($featureTerms);
        $deck->themes()->attach($themeTerms);
        $deck->colors()->attach($colorTerms);
        $deck->styles()->attach($styleTerms);
        $deck->stock()->attach($cardStockTerms);
        $deck->tags()->attach($tagTerms);

        if (isset($data['gallery']) && is_array($data['gallery'])) {
            foreach ($data['gallery'] as $gallery_id) {
                $gallery_item = GalleryItem::where('id', $gallery_id)->where('deck_id', 0)->where('user_id', $user->id)->first();
                if ($gallery_item) {
                    $gallery_item->deck_id = $deck->id;
                    $gallery_item->save();
                }
            }
        }
    }

    protected function createUsersDeck(Deck $deck, User $user) {
        return UsersDeck::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
        ]);
    }
    
}

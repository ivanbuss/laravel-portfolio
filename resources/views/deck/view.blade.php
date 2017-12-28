@extends('layouts.app')

@section('content')
    {{--{{ dump($deck) }}--}}
    @include('header-white')
    <div class="container-fluid deck-page-header">
        <div class="container deck-info-wrapper no-padding">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 no-padding">
                <img src="{{ $deck->getFrontImg('700x500') }}" class="img-responsive card-img lightbox-img">
                <div class='shareaholic-canvas' data-app='share_buttons' data-app-id='25450670'></div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 deck-info no-padding">
                <h1>{{ strtoupper($deck->name) }}</h1>
                <h4>{{ ($deck->company ? $deck->company->name :"") }} // Year {{ $deck->created_at->format('Y') }}</h4>
                <div class="rating">
                    {!! Form::open(['action'=>'RatingController@rate', 'id'=>'rate-form']) !!}
                        {!! Form::hidden('type', 'Deck') !!}
                        {!! Form::hidden('id', $deck->id) !!}
                        {!! Form::select('rating', [
                            1 => 1,
                            2 => 2,
                            3 => 3,
                            4 => 4,
                            5 => 5,
                        ], $deck->getRating(), [
                            'class' => 'rating-select',
                            'placeholder' => '',
                        ]) !!}
                    {!! Form::close() !!}
                </div>
                    <div class="">
                        SAVE TO PORTFOLIO
                    </div>
                <div id="animated-icons" class="animated-icons-large">
                    <div class="collection-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ($collection_items == 0 ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        {!! Form::open(['action'=>['CollectionController@postAdd'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                            {!! Form::hidden('deck_id', $deck->id) !!}
                            <div rel="{{ $deck->id }}" class="adding deck-view-collections">
                                <span class="number">{{ $collection_items ? $collection_items : 0 }}</span>
                            </div>
                        {!! Form::close() !!}
                        {!! Form::open(['action'=>['CollectionController@postRemove'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                        {!! Form::hidden('deck_id', $deck->id) !!}
                            <div class="minus deleting">
                                <span class="description">COLLECTION</span>
                                <i rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="wishlist-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ($wishlist_items == 0 ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        {!! Form::open(['action'=>['WishlistController@postAdd'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                            {!! Form::hidden('deck_id', $deck->id) !!}
                            <div rel="{{ $deck->id }}" class="number adding deck-view-collections">
                                <span class="number">{{ $wishlist_items ? $wishlist_items : 0 }}</span>
                            </div>
                        {!! Form::close() !!}
                        {!! Form::open(['action'=>['WishlistController@postRemove'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                            {!! Form::hidden('deck_id', $deck->id) !!}
                            <div class="minus deleting">
                                <span class="description">WISHLIST</span>
                                <i rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tradelist-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ($tradelist_items == 0 ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        {!! Form::open(['action'=>['TradelistController@postAdd'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                            {!! Form::hidden('deck_id', $deck->id) !!}
                            <div rel="{{ $deck->id }}" class="number adding deck-view-collections">
                                <span class="number">{{ $tradelist_items ? $tradelist_items : 0 }}</span>
                            </div>
                        {!! Form::close() !!}
                        {!! Form::open(['action'=>['TradelistController@postRemove'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                            {!! Form::hidden('deck_id', $deck->id) !!}
                            <div class="minus deleting">
                                <span class="description">TRADELIST</span>
                                <i rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="animated-icon-item disabled col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <div class="number adding">
                            <span class="number"></span>
                        </div>
                        <div class="minus deleting">
                            <span class="description">UNLOCK</span>
                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                @if (Auth::user())
                    <div class="notes">
                        <h4>MY NOTES</h4>
                        {!! Form::open(['action'=>['DeckEditController@postNotes', 'id'=>$deck->id], 'id'=>'deck-notes']) !!}
                            {!! Form::textarea('notes', $notes, ['class'=>'form-control', 'rows'=>6, 'placeholder'=>'Add your notes here']) !!}
                            {!! Form::submit('Save', ['class'=>'save']) !!}
                        {!! Form::close() !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container-fluid deck-page-main no-padding">
        <div class="deck-tab">
            <div class="container">DETAILS</div>
        </div>
        <div class="container">
            <h4 class="deck-title">ABOUT THIS DECK</h4>
            <div class="deck-main-info">
                {!! strip_tags($deck->description, '<a><p><ul><li><b><i><em><strong>') !!}
            </div>
            <div class="deck-fields">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p><span class="field-name">COMPANY:</span>{{ $deck->company ? $deck->company->name : 'N/A' }}</p>
                        <p><span class="field-name">EDITION:</span>{{ $deck->edition ? $deck->edition : 'N/A' }}</p>
                        <p><span class="field-name">COLLECTION</span>{{ $deck->brand ? $deck->brand->name : 'N/A' }}</p>
                        <p><span class="field-name">RELEASE YEAR:</span>{{ $deck->release_year ? $deck->release_year : 'N/A' }}</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p><span class="field-name">PRODUCTION RUN:</span>{{ $deck->prod_run ? $deck->prod_run : 'N/A' }}</p>
                        <p><span class="field-name">PRINTER:</span>{{ $deck->manufacturer ? $deck->manufacturer->name : 'N/A' }}</p>
                        <p><span class="field-name">ARTIST:</span>{{ $deck->artist ? $deck->artist->name : 'N/A' }}</p>
                        <p><a class="expand" role="button" data-toggle="collapse" href="#allDeckFields" aria-expanded="false" aria-controls="collapseExample">
                                (+)
                            </a>
                        </p>
                    </div>
                </div>
                <div class="collapse row" id="allDeckFields">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p><span class="field-name">CARD STOCK:</span>{{ $deck->stocklist ? $deck->stocklist : 'N/A' }}</p>
                        <p><span class="field-name">FINISH:</span>{{ $deck->finish ? $deck->finish : 'N/A' }}</p>
                        <p><span class="field-name">COURT ILLUSTRATION:</span>{{ $deck->customization ? $deck->customization : 'N/A' }}</p>
                        <p><span class="field-name">EXT. BOX FEATURES:</span><span class="field-value field-features">{{ $deck->featurelist ? $deck->featurelist : 'N/A' }} @if ($recent_changes['features'])<a class="cancel-tags" rel="features" role="button" href="{{ action('DeckEditController@postTermsReset', ['id'=>$deck->id]) }}">(Cancel)</a> @endif</span> <span><a class="expand" role="button" data-toggle="collapse" href="#add-tag-features" aria-expanded="false" aria-controls="collapseExample">(+)</a></span></p>
                        <div id="add-tag-features" class="collapse">
                            {!! Form::open(['action'=>['DeckEditController@postTermsEdit', 'id'=>$deck->id], 'class'=>'form-inline collapse-edit-tags']) !!}
                                {!! Form::hidden('name', 'features') !!}
                                {!! Form::text('value', null, ['id'=>'features', 'class'=>'form-control multiple autocomplete-term-multiple']) !!}
                                {!! Form::submit('+', ['class'=>'btn btn-default edit-tags']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p><span class="field-name">CARD COLORS:</span><span class="field-value field-colors">{{ $deck->colorlist ? $deck->colorlist : 'N/A' }} @if ($recent_changes['colors'])<a class="cancel-tags" rel="colors" role="button" href="{{ action('DeckEditController@postTermsReset', ['id'=>$deck->id]) }}">(Cancel)</a> @endif</span> <span><a class="expand" role="button" data-toggle="collapse" href="#add-tag-colors" aria-expanded="false" aria-controls="collapseExample">(+)</a></span></p>
                        <div id="add-tag-colors" class="collapse">
                            {!! Form::open(['action'=>['DeckEditController@postTermsEdit', 'id'=>$deck->id], 'class'=>'form-inline collapse-edit-tags']) !!}
                                {!! Form::hidden('name', 'colors') !!}
                                {!! Form::text('value', null, ['id'=>'colors', 'class'=>'form-control multiple autocomplete-term-multiple']) !!}
                                {!! Form::submit('+', ['class'=>'btn btn-default edit-tags']) !!}
                            {!! Form::close() !!}
                        </div>
                        <p><span class="field-name">STYLE:</span>{{ $deck->stylelist ? $deck->stylelist : 'N/A' }}</p>
                        <p><span class="field-name">THEMES:</span><span class="field-value field-themes">{{ $deck->themelist ? $deck->themelist : 'N/A' }} @if ($recent_changes['themes'])<a class="cancel-tags" rel="themes" role="button" href="{{ action('DeckEditController@postTermsReset', ['id'=>$deck->id]) }}">(Cancel)</a> @endif</span> <span><a class="expand" role="button" data-toggle="collapse" href="#add-tag-themes" aria-expanded="false" aria-controls="collapseExample">(+)</a></span></p>
                        <div id="add-tag-themes" class="collapse">
                            {!! Form::open(['action'=>['DeckEditController@postTermsEdit', 'id'=>$deck->id], 'class'=>'form-inline collapse-edit-tags']) !!}
                                {!! Form::hidden('name', 'themes') !!}
                                {!! Form::text('value', null, ['id'=>'themes', 'class'=>'form-control multiple autocomplete-term-multiple']) !!}
                                {!! Form::submit('+', ['class'=>'btn btn-default edit-tags']) !!}
                            {!! Form::close() !!}
                        </div>
                        <p><span class="field-name">ADDITIONAL TAGS:</span><span class="field-value field-tags">{{ $deck->taglist ? $deck->taglist : 'N/A' }} @if ($recent_changes['tags'])<a class="cancel-tags" rel="tags" role="button" href="{{ action('DeckEditController@postTermsReset', ['id'=>$deck->id]) }}">(Cancel)</a> @endif</span> <span><a class="expand" role="button" data-toggle="collapse" href="#add-tag-tags" aria-expanded="false" aria-controls="collapseExample">(+)</a></span></p>
                        <div id="add-tag-tags" class="collapse">
                            {!! Form::open(['action'=>['DeckEditController@postTermsEdit', 'id'=>$deck->id], 'class'=>'form-inline collapse-edit-tags']) !!}
                                {!! Form::hidden('name', 'tags') !!}
                                {!! Form::text('value', null, ['id'=>'tags', 'class'=>'form-control multiple autocomplete-term-multiple']) !!}
                                {!! Form::submit('+', ['class'=>'btn btn-default edit-tags']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="deck-gallery">
                <h4>GALLERY</h4>
                <div class="row">
                    <?php $gallery_counter = 1 ?>
                    @foreach($gallery as $gallery_item)
                        @if ($gallery_counter < 4)
                            <a class="galleryCboxElement col-lg-3 col-md-3 col-sm-3 col-xs-12" href="{{ $gallery_item->getImage('original') }}">
                                <img src="{{ $gallery_item->getImage('700x500') }}" class="img-responsive card-img">
                            </a>
                            <?php $gallery_counter++ ?>
                        @else
                            <a id="gallery-last-item" class="galleryCboxElement col-lg-3 col-md-3 col-sm-3 col-xs-12"  href="{{ $gallery_item->getImage('original') }}">
                                <img src="{{ $gallery_item->getImage('700x500') }}" class="img-responsive card-img">
                                <div class="dark-bg">
                                    <span class="see-all">See all {{ $gallery->count() }} photos</span>
                                </div>
                            </a>
                            @break
                        @endif
                    @endforeach
                </div>
                <div class="gallery-colorbox-items">
                    @foreach($gallery as $gallery_item)
                        <a class="group2 galleryCboxElement" href="{{ $gallery_item->getImage('original') }}"></a>
                    @endforeach
                </div>
            </div>
            <div class="deck-numbers">
                <h4>THIS DECK IS COLLECTED IN..</h4>
                <div class="deck-number-item">
                    <span class="number">{{ $in_collections }}</span>
                    <span class="info">COLLECTIONS</span>
                </div>
                <div class="deck-number-item">
                    <span class="number">{{ $in_wishlist }}</span>
                    <span class="info">WISHLISTS</span>
                </div>
                <div class="deck-number-item">
                    <span class="number">{{ $in_tradelist }}</span>
                    <span class="info">TRADELISTS</span>
                </div>
            </div>
        </div>
    <!--recommendations-->
        <div class="container-fluid no-padding recommendations-block">
            <h4>recommendations</h4>
            <div class="cards">
                <div class="container">
                    @foreach($recomandations as $deck)
                        <div class="card-wrapper col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div href="#inline_content{{ $deck->id }}" class="collection-item item-wrapper inline cboxElement group1">
                                <span class="big-img-card">
                                    <a href="{{ action('DeckViewController@getView', ['id'=>$deck->id]) }}">
                                        <img src="{{ $deck->getFrontImg('700x500') }}" class="img-responsive card-img front-img">
                                    </a>
                                    <a href="{{ action('DeckViewController@getView', ['id'=>$deck->id]) }}">
                                        <img src="{{ $deck->getBackImg('700x500') }}" class="img-responsive card-img back-img">
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="lightbox-items">
                            <div class="collection-card-lightbox group1" id="inline_content{{ $deck->id }}">
                                <div class="container deck-info-wrapper no-padding">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding">
                                        <img src="{{ $deck->getFrontImg('700x500') }}" class="img-responsive card-img lightbox-img">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 deck-info no-padding">
                                        <h1>{{ $deck->name }}</h1>
                                        <h4>{{ ($deck->company ? $deck->company->name :"") }} // {{ $deck->release_year }}</h4>
                                        <div class="rating border-top-grey border-bottom-grey">
                                            {!! Form::select('rating', [
                                            1 => 1,
                                            2 => 2,
                                            3 => 3,
                                            4 => 4,
                                            5 => 5,
                                            ], $deck->getRating(), [
                                            'class' => 'rating-select'
                                            ]) !!}
                                            <div class="description">{!! strip_tags($deck->description, '<a><p><ul><li><b><i><em><strong>') !!}</div>
                                        </div>
                                        <div class="border-bottom-grey portfolio-section">
                                            @if (Auth::user())
                                                <h4>YOUR PORTFOLIO</h4>
                                                <div id="animated-icons" class="animated-icons-small row">
                                                    <div class="collection-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ((!$deck->users_deck || $deck->users_deck->in_collection == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        {!! Form::open(['action'=>['CollectionController@postAdd'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $deck->id) !!}
                                                        <div rel="{{ $deck->id }}" class="adding deck-view-collections">
                                                            <span class="number">{{ (($deck->users_deck && $deck->users_deck->in_collection > 0) ? $deck->users_deck->in_collection : 0) }}</span>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        {!! Form::open(['action'=>['CollectionController@postRemove'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $deck->id) !!}
                                                        <div class="minus deleting">
                                                            <span class="description">COLLECTION</span>
                                                            <i rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                    <div class="wishlist-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ((!$deck->users_deck || $deck->users_deck->in_wishlist == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        {!! Form::open(['action'=>['WishlistController@postAdd'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $deck->id) !!}
                                                        <div rel="{{ $deck->id }}" class="number adding deck-view-collections">
                                                            <span class="number">{{ (($deck->users_deck && $deck->users_deck->in_wishlist) > 0 ? $deck->users_deck->in_wishlist : 0) }}</span>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        {!! Form::open(['action'=>['WishlistController@postRemove'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $deck->id) !!}
                                                        <div class="minus deleting">
                                                            <span class="description">WISHLIST</span>
                                                            <i rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                    <div class="tradelist-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ((!$deck->users_deck || $deck->users_deck->in_tradelist == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        {!! Form::open(['action'=>['TradelistController@postAdd'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $deck->id) !!}
                                                        <div rel="{{ $deck->id }}" class="number adding deck-view-collections">
                                                            <span class="number">{{ (($deck->users_deck && $deck->users_deck->in_tradelist) > 0 ? $deck->users_deck->in_tradelist : 0) }}</span>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        {!! Form::open(['action'=>['TradelistController@postRemove'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $deck->id) !!}
                                                        <div class="minus deleting">
                                                            <span class="description">TRADELIST</span>
                                                            <i rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                    <div class="animated-icon-item disabled col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        <div class="number adding">
                                                            <span class="number"></span>
                                                        </div>
                                                        <div class="minus deleting">
                                                            <span class="description">UNLOCK</span>
                                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @if (Auth::user())
                                            <div class="notes">
                                                <h4>MY NOTES</h4>
                                                {!! Form::open(['action'=>['DeckEditController@postNotes', 'id'=>$deck->id], 'id'=>'deck-notes']) !!}
                                                {!! Form::textarea('notes', $deck->users_deck ? $deck->users_deck->notes : null, ['class'=>'form-control', 'rows'=>6, 'placeholder'=>'Add your notes here']) !!}
                                                {!! Form::submit('Save', ['class'=>'save']) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
                                        <a class="btn-red col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ action('DeckViewController@getView', ['id'=>$deck->id]) }}">FULL PAGE INFO</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@endsection
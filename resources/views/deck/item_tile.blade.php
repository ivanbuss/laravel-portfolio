{{--<div class="table-items">--}}
@foreach($decks as $deck)
    <div class="card-wrapper col-lg-3 col-md-3 col-sm-3 col-xs-12 ">
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
        {{--<div class="decks-description">--}}
            {{--<span class="deck-title">{{ $deck->name }}</span>--}}
            {{-- Display buttons allowing adding to collections only if the user is logged in --}}
            {{--@if($user)--}}
                {{--{!! Form::open(['action'=>['CollectionController@postAdd'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}--}}
                {{--{!! Form::hidden('deck_id', $deck->id) !!}--}}
                {{--<a rel="{{ $deck->id }}" href="/" class="collection-deck-icon collection-add{{ $deck->inCollection($user) ? ' collection-deck-success' : '' }}"><i class="fa fa-plus" aria-hidden="true"></i></a>--}}
                {{--{!! Form::close() !!}--}}
                {{--{!! Form::open(['action'=>['WishlistController@postAdd'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}--}}
                {{--{!! Form::hidden('deck_id', $deck->id) !!}--}}
                {{--<a rel="{{ $deck->id }}" href="/" class="collection-deck-icon wishlist-add{{ $deck->inWishlist($user) ? ' collection-deck-success' : '' }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>--}}
                {{--{!! Form::close() !!}--}}
                {{--{!! Form::open(['action'=>['TradelistController@postAdd'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}--}}
                {{--{!! Form::hidden('deck_id', $deck->id) !!}--}}
                {{--<a rel="{{ $deck->id }}" href="/" class="collection-deck-icon tradelist-add{{ $deck->inTradelist($user) ? ' collection-deck-success' : '' }}"><i class="fa fa-heart" aria-hidden="true"></i></a>--}}
                {{--{!! Form::close() !!}--}}
            {{--@endif--}}
        {{--</div>--}}
    </div>
    {{--{{ dump($deck) }}--}}
    <div class="lightbox-items">
        <div class="collection-card-lightbox group1" id="inline_content{{ $deck->id }}">
            <div class="container deck-info-wrapper no-padding colorbox-deck-image">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding colorbox-deck-image">
                    <img src="{{ $deck->getFrontImg('700x500') }}" class="img-responsive card-img lightbox-img">
                    <img src="{{ $deck->getBackImg('700x500') }}" class="back-image img-responsive card-img back-img lightbox-img">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 deck-info no-padding">
                    <h1>{{ $deck->name }}</h1>
                    <h4>{{ ($deck->company ? $deck->company->name :"") }}// {{ $deck->release_year }}</h4>
                    <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} class="rating border-top-grey border-bottom-grey">
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
                        <div class="description">{!! strip_tags($deck->description, '<a><p><ul><li><b><i><em><strong>') !!}</div>
                    </div>
                    <div class="border-bottom-grey portfolio-section">
                        <h4>YOUR PORTFOLIO</h4>
                        <div id="animated-icons" class="animated-icons-small row">
                            <div class="collection-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ((!$deck->users_deck || $deck->users_deck->in_collection == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                {!! Form::open(['action'=>['CollectionController@postAdd'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                {!! Form::hidden('deck_id', $deck->id) !!}
                                <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $deck->id }}" class="adding deck-view-collections">
                                    <span class="number">{{ (($deck->users_deck && $deck->users_deck->in_collection > 0) ? $deck->users_deck->in_collection : 0) }}</span>
                                </div>
                                {!! Form::close() !!}
                                {!! Form::open(['action'=>['CollectionController@postRemove'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                {!! Form::hidden('deck_id', $deck->id) !!}
                                <div class="minus deleting">
                                    <span class="description">COLLECTION</span>
                                    <i{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class="wishlist-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ((!$deck->users_deck || $deck->users_deck->in_wishlist == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                {!! Form::open(['action'=>['WishlistController@postAdd'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                {!! Form::hidden('deck_id', $deck->id) !!}
                                <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $deck->id }}" class="number adding deck-view-collections">
                                    <span class="number">{{ (($deck->users_deck && $deck->users_deck->in_wishlist) > 0 ? $deck->users_deck->in_wishlist : 0) }}</span>
                                </div>
                                {!! Form::close() !!}
                                {!! Form::open(['action'=>['WishlistController@postRemove'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                {!! Form::hidden('deck_id', $deck->id) !!}
                                <div class="minus deleting">
                                    <span class="description">WISHLIST</span>
                                    <i{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class="tradelist-actions collection-deck-{{ $deck->id }} animated-icon-item{{ ((!$deck->users_deck || $deck->users_deck->in_tradelist == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                {!! Form::open(['action'=>['TradelistController@postAdd'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                {!! Form::hidden('deck_id', $deck->id) !!}
                                <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $deck->id }}" class="number adding deck-view-collections">
                                    <span class="number">{{ (($deck->users_deck && $deck->users_deck->in_tradelist) > 0 ? $deck->users_deck->in_tradelist : 0) }}</span>
                                </div>
                                {!! Form::close() !!}
                                {!! Form::open(['action'=>['TradelistController@postRemove'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                {!! Form::hidden('deck_id', $deck->id) !!}
                                <div class="minus deleting">
                                    <span class="description">TRADELIST</span>
                                    <i{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class="animated-icon-item disabled col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} class="number adding">
                                    <span class="number"></span>
                                </div>
                                <div class="minus deleting">
                                    <span class="description">UNLOCK</span>
                                    <i{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} class="fa fa-minus pull-right" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="notes">
                        <h4>MY NOTES</h4>
                        @if (Auth::user())
                            {!! Form::open(['action'=>['DeckEditController@postNotes', 'id'=>$deck->id], 'id'=>'deck-notes']) !!}
                                {!! Form::textarea('notes', $deck->users_deck ? $deck->users_deck->notes : null, ['class'=>'form-control', 'rows'=>6, 'placeholder'=>'Add your notes here']) !!}
                                {!! Form::submit('Save', ['class'=>'save']) !!}
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['action'=>['DeckEditController@postNotes', 'id'=>$deck->id], 'id'=>'deck-notes', 'data-toggle'=>'modal', 'data-target'=>'#signUpModal']) !!}
                                {!! Form::textarea('notes', $deck->users_deck ? $deck->users_deck->notes : null, ['class'=>'form-control', 'rows'=>6, 'placeholder'=>'Add your notes here']) !!}
                                {!! Form::submit('Save', ['class'=>'save']) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                    <a{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} class="btn-red col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ action('DeckViewController@getView', ['id'=>$deck->id]) }}">FULL PAGE INFO</a>
                </div>
            </div>
        </div>
    </div>
@endforeach
{{--</div>--}}
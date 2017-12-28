@extends('layouts.app')

@section('content')
    <header class="container-fluid main-header no-padding">
        <div class="container">

            @include('header-transparent')

            <div class="header-main-info">
                <div class="inner">
                    <h2>MAKE COLLECTING EASIER</h2>
                    <h6>Discover unique decks from the world’s largest playing card database</h6>
                    <a class="btn btn-red" href="#">How It works</a>
                </div>
            </div>
        </div>
        <div class="search-wrapper">
            <div class="container">
                <div class="input-group">
                    {!! Form::open(['action'=>['SearchController@searchPost'], 'id'=>'home-page-search', 'class'=>'input-group']) !!}
                    {!! Form::text('search_string', null, ['class' => 'form-control','id'=>'home-page-search-input', 'placeholder' => 'Find the perfect deck for your collection']) !!}

                    <span class="input-group-btn">
                        {!! Form::submit('Search', ['class' =>"btn btn-red"]) !!}
                    </span>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid main">
        <div class="container main-numbers">
            <h3>OUR MEMBERS HAVE</br> ALREADY ADDED</h3>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 numbers-item">
                {{--<span class="number" id="deck-numbers1">{{ $decks_amount }}</span>--}}
                <span class="number counter1" id="deck-numbers1" data-counter1="{{ $decks_amount }}">0</span>
                <span class="number-description">UNIQUE DECKS TO THE ARCHIVES</span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 numbers-item">
                {{--<span class="number" id="deck-numbers2" >{{ $decks_collection_amount }}</span>--}}
                <span class="number counter2" id="deck-numbers2" data-counter2="{{ $decks_collection_amount }}">0</span>
                <span class="number-description">DECKS TO THEIR COLLECTIONS</span>
            </div>
        </div>
        <div class="container-fluid recently-added-block">
            <div class="container">
                <h4>RECENTLY ADDED</h4>
                <div class="cards">
                    @foreach($recent_added as $deck)
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
                        <div class="lightbox-items">
                            <div class="collection-card-lightbox group1" id="inline_content{{ $deck->id }}">
                                <div class="container deck-info-wrapper no-padding">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 no-padding colorbox-deck-image">
                                        <img src="{{ $deck->getFrontImg('700x500') }}" class="img-responsive card-img lightbox-img">
                                        <img src="{{ $deck->getBackImg('700x500') }}" class="back-image img-responsive card-img back-img lightbox-img">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 deck-info no-padding">
                                        <h1>{{ $deck->name }}</h1>
                                        <h4>{{ ($deck->company ? $deck->company->name :"") }} // {{ $deck->release_year }}</h4>
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
                                        @if (Auth::user())
                                            <a class="btn-red col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ action('DeckViewController@getView', ['id'=>$deck->id]) }}">FULL PAGE INFO</a>
                                        @else
                                            <a data-toggle="modal" data-target="#signUpModal" class="btn-red col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ action('DeckViewController@getView', ['id'=>$deck->id]) }}">FULL PAGE INFO</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="container-fluid recently-collected-block">
            <div class="container">
                <h4>RECENTLY COLLECTED</h4>
                <div class="cards cards-with-user">
                    {{--TODO: changed recent_added to recent_collected--}}
                    @foreach($recent_collected as $collection)
                        <div class="card-wrapper col-lg-3 col-md-3 col-sm-3 col-xs-12 ">
                            <div href="#inline_content{{ $collection->deck->id }}" class="collection-item item-wrapper inline cboxElement group1">
                                   <span class="big-img-card">
                                    <a href="{{ action('DeckViewController@getView', ['id'=>$collection->deck->id]) }}">
                                        <img src="{{ $collection->deck->getFrontImg('700x500') }}" class="img-responsive card-img front-img">
                                    </a>
                                    <a href="{{ action('DeckViewController@getView', ['id'=>$collection->deck->id]) }}">
                                        <img src="{{ $collection->deck->getBackImg('700x500') }}" class="img-responsive card-img back-img">
                                    </a>
                                    </span>
                                {{--<a href="{{ action('DeckViewController@getView', ['id'=>$collection->deck->id]) }}"><img src="{{ $collection->deck->getFrontImg('700x500') }}" class="img-responsive card-img front-img"></a>--}}
                                {{--<a href="{{ action('DeckViewController@getView', ['id'=>$collection->deck->id]) }}"><img src="{{ $collection->deck->getBackImg('700x500') }}" class="img-responsive card-img back-img"></a>--}}
                            </div>
                            <div class="user">
                                <a href="profile/{{ $collection->user->id }}"><img src="{{ $collection->user->profile->getAvatarImg('300x300') }}" class="img-responsive img-circle pull-left"></a>
                                <a href="profile/{{ $collection->user->id }}"><span class="user-name">{{ $collection->user->profile->name }}</span></a>
                                <span class="time">Added {{ $collection->addedAgo() }} ago</span>
                            </div>
                        </div>
                        <div class="lightbox-items">
                            <div class="collection-card-lightbox group1" id="inline_content{{ $collection->deck->id }}">
                                <div class="container deck-info-wrapper no-padding">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding colorbox-deck-image">
                                        <img src="{{ $collection->deck->getFrontImg('700x500') }}" class="front-image img-responsive card-img lightbox-img">
                                        <img src="{{ $collection->deck->getBackImg('700x500') }}" class="back-image img-responsive card-img back-img lightbox-img">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 deck-info no-padding">
                                        <h1>{{ $collection->deck->name }}</h1>
                                        <h4>{{ ($collection->deck->company ? $collection->deck->company->name :"") }} // {{ $collection->deck->release_year }}</h4>
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
                                            <div class="description">{!! strip_tags($collection->deck->description, '<a><p><ul><li><b><i><em><strong>') !!}</div>
                                        </div>
                                        <div class="border-bottom-grey portfolio-section">
                                            @if (Auth::user())
                                                <h4>YOUR PORTFOLIO</h4>
                                                <div id="animated-icons" class="animated-icons-small row">
                                                    <div class="collection-actions collection-deck-{{ $collection->deck->id }} animated-icon-item{{ ((!$collection->deck->users_deck || $collection->deck->users_deck->in_collection == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        {!! Form::open(['action'=>['CollectionController@postAdd'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $collection->deck->id) !!}
                                                        <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $collection->deck->id }}" class="adding deck-view-collections">
                                                            <span class="number">{{ (($collection->deck->users_deck && $collection->deck->users_deck->in_collection > 0) ? $collection->deck->users_deck->in_collection : 0) }}</span>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        {!! Form::open(['action'=>['CollectionController@postRemove'], 'id'=>'collection-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $collection->deck->id) !!}
                                                        <div class="minus deleting">
                                                            <span class="description">COLLECTION</span>
                                                            <i{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $collection->deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                    <div class="wishlist-actions collection-deck-{{ $collection->deck->id }} animated-icon-item{{ ((!$collection->deck->users_deck || $collection->deck->users_deck->in_wishlist == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        {!! Form::open(['action'=>['WishlistController@postAdd'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $collection->deck->id) !!}
                                                        <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $collection->deck->id }}" class="number adding deck-view-collections">
                                                            <span class="number">{{ (($collection->deck->users_deck && $collection->deck->users_deck->in_wishlist) > 0 ? $collection->deck->users_deck->in_wishlist : 0) }}</span>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        {!! Form::open(['action'=>['WishlistController@postRemove'], 'id'=>'wishlist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $collection->deck->id) !!}
                                                        <div class="minus deleting">
                                                            <span class="description">WISHLIST</span>
                                                            <i{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $collection->deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                    <div class="tradelist-actions collection-deck-{{ $collection->deck->id }} animated-icon-item{{ ((!$collection->deck->users_deck || $collection->deck->users_deck->in_tradelist == 0) ? ' disabled' : '') }} col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        {!! Form::open(['action'=>['TradelistController@postAdd'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $collection->deck->id) !!}
                                                        <div{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $collection->deck->id }}" class="number adding deck-view-collections">
                                                            <span class="number">{{ (($collection->deck->users_deck && $collection->deck->users_deck->in_tradelist) > 0 ? $collection->deck->users_deck->in_tradelist : 0) }}</span>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        {!! Form::open(['action'=>['TradelistController@postRemove'], 'id'=>'tradelist-add-form', 'class'=>'collection-icon-wrapper']) !!}
                                                        {!! Form::hidden('deck_id', $collection->deck->id) !!}
                                                        <div class="minus deleting">
                                                            <span class="description">TRADELIST</span>
                                                            <i{{ (Auth::guest() ? ' data-toggle=modal data-target=#signUpModal' : '') }} rel="{{ $collection->deck->id }}" class="fa fa-minus pull-right deck-view-collections" aria-hidden="true"></i>
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
                                            @endif
                                        </div>
                                        <div class="notes">
                                            <h4>MY NOTES</h4>
                                            @if (Auth::user())
                                                {!! Form::open(['action'=>['DeckEditController@postNotes', 'id'=>$collection->deck->id], 'id'=>'deck-notes']) !!}
                                                    {!! Form::textarea('notes', $collection->deck->users_deck ? $collection->deck->users_deck->notes : null, ['class'=>'form-control', 'rows'=>6, 'placeholder'=>'Add your notes here']) !!}
                                                    {!! Form::submit('Save', ['class'=>'save']) !!}
                                                {!! Form::close() !!}
                                            @else
                                                {!! Form::open(['action'=>['DeckEditController@postNotes', 'id'=>$collection->deck->id], 'id'=>'deck-notes', 'data-toggle'=>'modal', 'data-target'=>'#signUpModal']) !!}
                                                    {!! Form::textarea('notes', $collection->deck->users_deck ? $collection->deck->users_deck->notes : null, ['class'=>'form-control', 'rows'=>6, 'placeholder'=>'Add your notes here']) !!}
                                                    {!! Form::submit('Save', ['class'=>'save']) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        </div>
                                        @if (Auth::user())
                                            <a class="btn-red col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ action('DeckViewController@getView', ['id'=>$collection->deck->id]) }}">FULL PAGE INFO</a>
                                        @else
                                            <a data-toggle="modal" data-target="#signUpModal" class="btn-red col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ action('DeckViewController@getView', ['id'=>$collection->deck->id]) }}">FULL PAGE INFO</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="inspirqation-awits">
        <div class="inner">
            <h3>INSPIRATION AWAITS</h3>
            <h6>Get immediate recommendations for your collections by creating </h6>
            @if(!Auth::user())
                <a class="btn btn-red" href="#" data-toggle="modal" data-target="#signUpModal">SIGN UP NOW</a>
            @endif
        </div>
    </div>

@endsection

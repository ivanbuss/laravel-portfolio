@extends('layouts.app')

@section('content')
    <div class="container-fluid user-header no-padding">
        @include('header-transparent')

        <div class="container deck-info-wrapper no-padding">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding">
                <img src="{{ $deck->getFrontImg('700x500') }}" class="img-responsive card-img lightbox-img">
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 deck-info no-padding">
                <h1>{{ strtoupper($deck->name) }}</h1>
                <h4>{{ ($deck->company ? $deck->company->name :"") }} // Year {{ $deck->created_at->format('Y') }}</h4>
                <h4>LAUNCHIES {{ $deck->launch_date->format('F n, Y - g:i A') }}</h4>

                <div id="animated-icons" class="animated-icons-small row">
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
                </div>

                <h1>{{ $days }} : {{ $hours }} : {{ $mins }}</h1>
            </div>
        </div>
    </div>
    <div class="container-fluid deck-page-main no-padding">
        <div class="container">
            @foreach($reviews as $review)
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <img src="{{ $review->getImage('700x500') }}" class="img-responsive review-img">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {!! strip_tags($review->body, '<a><p><ul><li><b><i><em><strong>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
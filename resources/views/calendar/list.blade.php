@extends('layouts.app')

@section('content')
    <div class="search-page">
        <div class="container-fluid search-page-header">
            <div class="container">
                @include('header-transparent')
                <div class="inspiration-wrapper">
                    <div class="inner">
                        <h1>PONDERING</h1>
                        <h1>PRIMAVERA</h1>
                        <h3>CURIOSITY COLLECTION</h3>
                        <a class="btn-red">LEARN MORE</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid grey-bg no-padding">
        <h3>Launch Calendar</h3>
        <div rel="search" class="container collection-list cards">
            <div class="container thumbnails">
                @foreach($decks as $deck)
                    <div class="card-wrapper col-lg-3 col-md-3 col-sm-3 col-xs-12 ">
                        <div href="#inline_content{{ $deck->id }}" class="collection-item item-wrapper inline">
                            <span class="big-img-card">
                                <a href="{{ action('LaunchCalendarController@getDeckView', ['id'=>$deck->id]) }}">
                                    <img src="{{ $deck->getFrontImg('700x500') }}" class="img-responsive card-img front-img">
                                </a>
                                <a href="{{ action('LaunchCalendarController@getDeckView', ['id'=>$deck->id]) }}">
                                    <img src="{{ $deck->getBackImg('700x500') }}" class="img-responsive card-img back-img">
                                </a>
                            </span>
                        </div>
                        <div class="decks-description">
                            <span class="deck-title">{{ $deck->name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

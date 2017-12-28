@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Decks rating report</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">By age</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <ul class="chart-dot-list">
                                        <li><a class="chart-deck-rated-age-filter" rel="0,15" onclick="return false;" href="#">0-15</a></li>
                                        <li><a class="chart-deck-rated-age-filter" rel="16,25" onclick="return false;" href="#">16-25</a></li>
                                        <li><a class="chart-deck-rated-age-filter" rel="26,40" onclick="return false;" href="#">26-40</a></li>
                                        <li><a class="chart-deck-rated-age-filter" rel="41,60" onclick="return false;" href="#">41-60</a></li>
                                        <li><a class="chart-deck-rated-age-filter" rel="61," onclick="return false;" href="#">60+</a></li>
                                        <li><a class="chart-deck-rated-age-filter" rel="" onclick="return false;" href="#">All</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="chart chart-doughnut">
                                        <canvas width="640" height="640" data-url="{{ action('Admin\DeckRatedController@postDeckReport', ['id'=>$deck->id]) }}" id="chart-deck-rated-age"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">By decks in collection</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <ul class="chart-dot-list">
                                        <li><a class="chart-deck-rated-collection-filter" rel="0,5" onclick="return false;" href="#">0-5</a></li>
                                        <li><a class="chart-deck-rated-collection-filter" rel="6,10" onclick="return false;" href="#">6-10</a></li>
                                        <li><a class="chart-deck-rated-collection-filter" rel="11,25" onclick="return false;" href="#">11-25</a></li>
                                        <li><a class="chart-deck-rated-collection-filter" rel="26,50" onclick="return false;" href="#">26-50</a></li>
                                        <li><a class="chart-deck-rated-collection-filter" rel="51," onclick="return false;" href="#">50+</a></li>
                                        <li><a class="chart-deck-rated-collection-filter" rel="" onclick="return false;" href="#">All</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="chart chart-doughnut">
                                        <canvas width="640" height="640" data-url="{{ action('Admin\DeckRatedController@postDeckReport', ['id'=>$deck->id]) }}" id="chart-deck-rated-collection"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">By gender</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <ul class="chart-dot-list">
                                        <li><a class="chart-deck-rated-gender-filter" rel="1" onclick="return false;" href="#">1 Star</a></li>
                                        <li><a class="chart-deck-rated-gender-filter" rel="2" onclick="return false;" href="#">2 Stars</a></li>
                                        <li><a class="chart-deck-rated-gender-filter" rel="3" onclick="return false;" href="#">3 Stars</a></li>
                                        <li><a class="chart-deck-rated-gender-filter" rel="4" onclick="return false;" href="#">4 Stars</a></li>
                                        <li><a class="chart-deck-rated-gender-filter" rel="5," onclick="return false;" href="#">5 Stars</a></li>
                                        <li><a class="chart-deck-rated-gender-filter" rel="" onclick="return false;" href="#">All</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="chart chart-doughnut">
                                        <canvas width="640" height="640" data-url="{{ action('Admin\DeckRatedController@postDeckReport', ['id'=>$deck->id]) }}" id="chart-deck-rated-gender"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">By location</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <ul class="chart-dot-list">
                                        <li><a class="chart-deck-rated-location-filter" rel="1" onclick="return false;" href="#">1 Star</a></li>
                                        <li><a class="chart-deck-rated-location-filter" rel="2" onclick="return false;" href="#">2 Stars</a></li>
                                        <li><a class="chart-deck-rated-location-filter" rel="3" onclick="return false;" href="#">3 Stars</a></li>
                                        <li><a class="chart-deck-rated-location-filter" rel="4" onclick="return false;" href="#">4 Stars</a></li>
                                        <li><a class="chart-deck-rated-location-filter" rel="5," onclick="return false;" href="#">5 Stars</a></li>
                                        <li><a class="chart-deck-rated-location-filter" rel="" onclick="return false;" href="#">All</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="chart chart-doughnut">
                                        <canvas width="640" height="640" data-url="{{ action('Admin\DeckRatedController@postDeckReport', ['id'=>$deck->id]) }}" id="chart-deck-rated-location"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
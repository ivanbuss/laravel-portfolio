@extends('layouts.app')

@section('content')
    <div class="search-page">
        {{--{{ link_to_action('SearchController@discoverView', 'view', app('request')->all()) }}--}}
        <div class="container-fluid search-page-header">
            <div class="inner">
                <h1>INSPIRATION AWAITS</h1>
                <h3>FIND YOUR NEXT DECK</h3>
                <a class="btn-red">RECOMMEND</a>
            </div>
        </div>
        <div class="container-fluid search-page-main-tabs no-padding">
            <div class="row box-shadow-small">
                <div id="search-main-tab-filter" class="search-main-tab-item search-main-tab-filter col-lg-6 col-md-6 col-sm-6 col-xs-12 active">
                    <h5 class="pull-right">FILTER</h5>
                </div>
                <div id="search-main-tab-discover" class="search-main-tab-item search-main-tab-discover col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h5>DISCOVER</h5>
                </div>
            </div>
            <div class="search-page-filter-tabs container-fluid box-shadow-small no-padding">
                <div class="container-fluid white-bg box-shadow-small">
                    <ul class="nav nav-tabs container">
                        <li class="tab active box-shadow-small"><a class="" data-target="#QUANTITY" data-toggle="tab">QUANTITY</a></li>
                        <li class="tab box-shadow-small"><a class="" data-target="#COLOR" data-toggle="tab">COLOR</a></li>
                        <li class="tab box-shadow-small"><a class="" data-target="#THEME" data-toggle="tab">THEME</a></li>
                        <li class="tab box-shadow-small"><a class="" data-target="#STOCK" data-toggle="tab">STOCK</a></li>
                        <li class="tab box-shadow-small"><a class="" data-target="#STYLE" data-toggle="tab">STYLE</a></li>
                    </ul>
                </div>
                <div class="tab-content container">
                    <div class="tab-pane" id="QUANTITY">
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12">0 - 1</div>
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12">1 - 2.5k</div>
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12">2.5k - 5k</div>
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12">5k - 10k</div>
                    </div>
                    <div class="tab-pane" id="COLOR">
                        <div class="search-filter-item color-item col-lg-3 col-md-3 col-sm-3 col-xs-12">red</div>
                        <div class="search-filter-item color-item col-lg-3 col-md-3 col-sm-3 col-xs-12">blue</div>
                        <div class="search-filter-item color-item col-lg-3 col-md-3 col-sm-3 col-xs-12">pink</div>
                        <div class="search-filter-item color-item col-lg-3 col-md-3 col-sm-3 col-xs-12">orange</div>
                        <div class="search-filter-item color-item col-lg-3 col-md-3 col-sm-3 col-xs-12">black</div>
                    </div>
                    <div class="tab-pane" id="THEME">
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">1 theme</div>
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">2 theme</div>
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">3 theme</div>
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">4 theme</div>
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">5 theme</div>
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">6 theme</div>
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">7 theme</div>
                        <div class="search-filter-item theme-item col-lg-3 col-md-3 col-sm-3 col-xs-12">8 theme</div>
                    </div>
                    <div class="tab-pane" id="STOCK">
                        <div class="search-filter-item stock-item col-lg-3 col-md-3 col-sm-3 col-xs-12">1stock</div>
                        <div class="search-filter-item stock-item col-lg-3 col-md-3 col-sm-3 col-xs-12">2stock</div>
                        <div class="search-filter-item stock-item col-lg-3 col-md-3 col-sm-3 col-xs-12">3stock</div>
                        <div class="search-filter-item stock-item col-lg-3 col-md-3 col-sm-3 col-xs-12">4stock</div>
                        <div class="search-filter-item stock-item col-lg-3 col-md-3 col-sm-3 col-xs-12">5stock</div>
                    </div>
                    <div class="tab-pane" id="STYLE">
                        <div class="search-filter-item style-item col-lg-3 col-md-3 col-sm-3 col-xs-12">1STYLE</div>
                        <div class="search-filter-item style-item col-lg-3 col-md-3 col-sm-3 col-xs-12">2STYLE</div>
                        <div class="search-filter-item style-item col-lg-3 col-md-3 col-sm-3 col-xs-12">3STYLE</div>
                        <div class="search-filter-item style-item col-lg-3 col-md-3 col-sm-3 col-xs-12">4STYLE</div>
                        <div class="search-filter-item style-item col-lg-3 col-md-3 col-sm-3 col-xs-12">5STYLE</div>
                        <div class="search-filter-item style-item col-lg-3 col-md-3 col-sm-3 col-xs-12">6STYLE</div>
                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid grey-bg no-padding">
            <div class=" view-icons discover-bar white-bg">
                <div class="container no-padding">
                    <div class="search-state-wrapper col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <span class="search-state-icon col-lg-1 col-md-1 col-sm-1 col-xs-1"></span>
                        <span class="title col-lg-2 col-md-2 col-sm-2 col-xs-2">SEARCH:</span>
                        <span class="search-state-items col-lg-9 col-md-9 col-sm-9 col-xs-9"></span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        {!! Form::open(array('url' => 'search/post','id'=>'search-form')) !!}
                        {!! Form::select('decks_sort', [
                        'newest' => 'newest',
                        'oldest' => 'oldest',
                        'alphabetical' => 'alphabetical',
                        ], null, ['id' => 'card-sorting','class'=>'form-control']) !!}
                        {!! Form::submit() !!}
                        {!! Form::close() !!}
                        <i id='table-view-icon' class="fa fa-table active view-icon" aria-hidden="true"></i>
                        <i id='list-view-icon' class="fa fa-list view-icon" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="container collection-list cards">
                @if(app('request')->input('view') === 'list')
                    @include('deck.item_list')
                @else
                    <div class="container thumbnails">
                        @include('deck.item_tile')
                    </div>
                @endif
                {{-- TODO: endless scrolling --}}
            </div>
        </div>
    </div>
@endsection

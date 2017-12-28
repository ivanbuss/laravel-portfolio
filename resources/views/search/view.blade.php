@extends('layouts.app')

@section('content')
    <div class="search-page">
        {{--{{ link_to_action('SearchController@discoverView', 'view', app('request')->all()) }}--}}
        <div class="container-fluid search-page-header">
            <div class="container">
                @include('header-transparent')
                <div class="inspiration-wrapper">
                    <div class="inner">
                        <h1>INSPIRATION AWAITS</h1>
                        <h3>FIND YOUR NEXT DECK</h3>
                        {{--<a class="btn-red">RECOMMEND</a>--}}
                    </div>
                </div>
            </div>

        </div>
        <div class="container-fluid search-page-main-tabs no-padding">
            <div class="box-shadow-small search-page-main-tabs-inner">
            @if ($active_tab === 'discover' )
                <div id="search-main-tab-filter" class="search-main-tab-item search-main-tab-filter col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h5 class="pull-right">FILTER</h5>
                </div>
                <div id="search-main-tab-discover" class="search-main-tab-item search-main-tab-discover col-lg-6 col-md-6 col-sm-6 col-xs-12 active">
                    <h5>DISCOVER</h5>
                </div>
            @else
                <div id="search-main-tab-filter" class="search-main-tab-item search-main-tab-filter col-lg-6 col-md-6 col-sm-6 col-xs-12 active">
                    <h5 class="pull-right">FILTER</h5>
                </div>
                <div id="search-main-tab-discover" class="search-main-tab-item search-main-tab-discover col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                    <h5>DISCOVER</h5>
                </div>
            @endif
            </div>
            <div class="search-page-filter-tabs container-fluid no-padding">
                <div class="container-fluid white-bg box-shadow-bottom ">
                    <ul class="nav nav-tabs container">
                        {{--<li class="tab box-shadow-small active"><a class="" data-target="#SEARCH-STRING" data-toggle="tab">Search</a></li>--}}
                        <li class="tab box-shadow-top-inner active"><a class="" data-target="#QUANTITY" data-toggle="tab">QUANTITY</a></li>
                        <li class="tab box-shadow-top-inner "><a class="" data-target="#COLOR" data-toggle="tab">COLOR</a></li>
                        <li class="tab box-shadow-top-inner"><a class="" data-target="#TUCK" data-toggle="tab">TUCK</a></li>
                        <li class="tab box-shadow-top-inner"><a class="" data-target="#STOCK" data-toggle="tab">STOCK</a></li>
                        <li class="tab box-shadow-top-inner"><a class="" data-target="#STYLE" data-toggle="tab">STYLE</a></li>
                    </ul>
                </div>
                <div class="tab-content container">
                    <div class="tab-pane active" id="QUANTITY">
                        <div class="description-tabs">QUANTITY is the number of decks produced for a specific run.</div>
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12" data-quantity-from="0" data-quantity-to="1000" >0 - 1</div>
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12" data-quantity-from="1000" data-quantity-to="2500">1 - 2.5k</div>
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12" data-quantity-from="2500" data-quantity-to="5000">2.5k - 5k</div>
                        <div class="search-filter-item quantity-item col-lg-3 col-md-3 col-sm-3 col-xs-12" data-quantity-from="5000" data-quantity-to="10000">5k - 10k</div>
                    </div>
                    <div class="tab-pane" id="COLOR">
                        <div class="description-tabs">Select as many of as few boxes to filter out decks of those specific COLORS</div>
                        @foreach($colors as $color_id => $color_name)
                        <div class="search-filter-item color-item col-lg-3 col-md-3 col-sm-3 col-xs-12" data-id="{{ $color_id }}">{{ $color_name }}</div>
                        @endforeach
                    </div>
                    {{-- @TODO change to "Tuck" --}}
                    <div class="tab-pane" id="TUCK">
                        <div class="description-tabs">Where packaging meets product, the tuck is the greeting
                            guard of your cards and the face your collection gallery. Select the traits that make up the perfect tuck that you are looking for.</div>
                        <div id="tuck-items-wrapper">
                            @foreach($features as $feature_id => $feature_name)
                                <div class="search-filter-item theme-item  tuck-item-design  col-xs-12" data-id="{{ $feature_id }}">{{ $feature_name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane" id="STOCK">
                        <div class="description-tabs">When performance matters, be sure to choose the right cards for you.
                            Specify your stock to find the best match for your needs.</div>
                        <div id="stock-items-wrapper">
                        @foreach($card_stock as $card_stock_id => $card_stock_name)
                            <div class="search-filter-item stock-item stock-item-design col-xs-12" data-id="{{ $card_stock_id }}">{{ $card_stock_name }}</div>
                        @endforeach
                        </div>
                    </div>
                    <div class="tab-pane" id="STYLE">
                        <div class="description-tabs">Pick a style that you can get behind</div>
                        <div id="style-items-wrapper">
                        @foreach($style as $style_id => $style_name)
                            <div class="search-filter-item style-item style-item-design col-xs-12" data-id="{{ $style_id }}">{{ $style_name }}</div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-->
            <div class="container hidden-element" id="SEARCH-STRING">
                <div class="tab-pane" id="STYLE">
                    <div class="description-tabs">Find the perfect deck for you<br> Use the search bar below and add filters to fine tune your search</div>
                <input id="search-string-field" value="{{ (isset($params['search_string']) && $params['search_string']) ? $params['search_string'] : '' }}" type="text" class="form-control" placeholder="Search for...">
            </div>
            <!-->
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
                        'newest' => 'Newest',
                        'oldest' => 'Oldest',
                        'alphabetical' => 'Alphabetical',
                        ], null, ['id' => 'card-sorting','class'=>'form-control']) !!}
                        {!! Form::submit() !!}
                        {!! Form::close() !!}
                        <i id='tile-view-icon' class="fa fa-table active view-icon black" aria-hidden="true"></i>
                        <i id='list-view-icon' class="fa fa-list view-icon black" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div rel="search" class="container collection-list cards">
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

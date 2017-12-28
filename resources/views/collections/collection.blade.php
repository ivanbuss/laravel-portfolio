@extends('layouts.app')

@section('content')

    <div class="container-fluid user-header no-padding">
        @include('header-white')
        <img src="{{ $profile->getBackgroundImg('450x1350') }}" alt="" class="user-bg-header img-responsive">
        <div class="container user-info">
            <a href="{{ url('/profile/' . $user->id) }}"><img src="{{ $profile->getAvatarImg('300x300') }}" alt="avatar" class="user-img img-responsive img-circle"></a>
            <h1 class="user-name">User: {{ $profile->name }}</h1>
            <p class="rank">{{ $profile->userRank() }}</p>
            <p class="bio">{{ $profile->bio }}</p>
        </div>
    </div>
    <div class="red-bg user-collections-title">
        <div class="container">
            <div col-md-6>COLLECTIONS</div>
        </div>
    </div>
    <div class="container-fluid grey-bg">
        <div class="container">
            <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
                <h2>MY COLLECTION <span class="amount">({{ $count }})</span></h2>
                <div class='social-links shareaholic-canvas' data-app='share_buttons' data-app-id='25418727'></div>
            </div>
            <div class="view-icons collections-view-icons discover-bar">
                <div class="container no-padding">
                    <div class="search-state-wrapper col-lg-8 col-md-8 col-sm-8 col-xs-12"></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        {!! Form::open(['action'=>['CollectionController@postSearch', 'id'=>$user->id],'id'=>'search-form']) !!}
                            {!! Form::select('decks_sort', [
                                'custom' => 'Custom Ordering',
                                'alphabetical' => 'Alphabetical',
                                'newest' => 'Recently Added',
                            ], null, ['id' => 'collection-card-sorting','class'=>'form-control']) !!}
                            {!! Form::submit() !!}
                        {!! Form::close() !!}
                        <i id='tile-view-icon' class="fa fa-table active view-icon" aria-hidden="true"></i>
                        <i id='list-view-icon' class="fa fa-list view-icon" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div rel="collection" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collection-list cards">
                <div id="{{ $sortable ? 'collection-sortable' : 'collection-list' }}" class="table-items" data-url="{{ action('CollectionController@postReorder') }}">
                    @include('collections.item_tile')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection

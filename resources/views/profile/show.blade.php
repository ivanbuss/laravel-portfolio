@extends('layouts.app')

@section('content')
    <div class="container-fluid user-header no-padding">
        @include('header-white')
        <img src="{{ $profile->getBackgroundImg('450x1350') }}" alt="" class="user-bg-header img-responsive ">
        <div class="container user-info">
            <img src="{{ $profile->getAvatarImg('300x300') }}" alt="avatar" class="user-img img-responsive img-circle">
            @if(Auth::user() && Auth::user()->id == $user->id)
                <a href="{{ route('profile.edit.get', [$user->id]) }}" class="btn pull-right">Edit profile</a>
            @endif
            <h1 class="user-name">User: {{ $profile->name }}</h1>
            <p class="rank">{{ $rank }}</p>
            <p class="bio">{{ $profile->bio }}</p>
        </div>
    </div>
    <div class="red-bg user-collections-title">
        <div class="container">
            <div col-md-6>PORTFOLIO</div>
        </div>
    </div>
    <div class="user-collections">
        <div class="container">
           <h2>{{ strtoupper($user->name) }}'S COLLECTIONS</h2>
            {{-- START MY COLLECTION --}}
            <a href="{{ (Auth::user() && $user->id == Auth::user()->id ? action('CollectionController@getList') : action('CollectionController@getUserList', ['id'=>$user->id])) }}" class="user-collections-item-wrapper col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="user-collections-item">
                    <div class="row cards no-margin">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding big-card">
                                @if( isset($collection_list[0]) )
                                    <img class="img-responsive" src="{{ $collection_list[0]->deck->getFrontImg('700x500') }}">
                                @else
                                    <div class="grey-bg">
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="row first-row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                        @if( isset($collection_list[1]) )
                                            <img class="img-responsive" src="{{ $collection_list[1]->deck->getFrontImg('350x250') }}">
                                        @else
                                            <div class="grey-bg">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                        @if( isset($collection_list[2]) )
                                            <img class="img-responsive" src="{{ $collection_list[2]->deck->getFrontImg('350x250') }}">
                                        @else
                                            <div class="grey-bg">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row second-row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                        @if( isset($collection_list[3]) )
                                            <img class="img-responsive" src="{{ $collection_list[3]->deck->getFrontImg('350x250') }}">
                                        @else
                                            <div class="grey-bg">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                        @if( isset($collection_list[4]) )
                                            <img class="img-responsive" src="{{ $collection_list[4]->deck->getFrontImg('350x250') }}">
                                        @else
                                            <div class="grey-bg">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row item-description-wrapper no-margin">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 item-description">
                            <h3>MY COLLECTION</h3>
                            <span class="decks-amount">{{ $collected_items_amount }} DECKS</span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 item-button">
                            <button class="btn btn-red">See more</button>
                        </div>
                    </div>
                    <div class="user-collections-item-bg">
                    </div>
                </div>
            </a>
            {{-- END MY COLLECTION --}}

            {{-- START WISHLIST --}}
            <a href="{{ (Auth::user() && $user->id == Auth::user()->id ? action('WishlistController@getList') : action('WishlistController@getUserList', ['id'=>$user->id])) }}" class="user-collections-item-wrapper col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="user-collections-item">
                    <div class="row cards no-margin">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding big-card">
                            @if( isset($wishlist[0]) )
                                <img class="img-responsive" src="{{ $wishlist[0]->deck->getFrontImg('700x500') }}">
                            @else
                                <div class="grey-bg">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="row first-row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($wishlist[1]) )
                                        <img class="img-responsive" src="{{ $wishlist[1]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($wishlist[2]) )
                                        <img class="img-responsive" src="{{ $wishlist[2]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row second-row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($wishlist[3]) )
                                        <img class="img-responsive" src="{{ $wishlist[3]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($wishlist[4]) )
                                        <img class="img-responsive" src="{{ $wishlist[4]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row item-description-wrapper no-margin">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 item-description">
                            <h3>MY WISHLIST</h3>
                            <span class="decks-amount">{{ $wishlist_items_amount }} DECKS</span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 item-button">
                            <button class="btn btn-red">See more</button>
                        </div>
                    </div>
                    <div class="user-collections-item-bg">
                    </div>
                </div>
            </a>
            {{-- END WISHLIST --}}

            {{-- START TRADELIST --}}
            <a href="{{ (Auth::user() && $user->id == Auth::user()->id ? action('TradelistController@getList') : action('TradelistController@getUserList', ['id'=>$user->id])) }}" class="user-collections-item-wrapper col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="user-collections-item">
                    <div class="row cards no-margin">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding big-card">
                            @if( isset($tradelist[0]) )
                                <img class="img-responsive" src="{{ $tradelist[0]->deck->getFrontImg('700x500') }}">
                            @else
                                <div class="grey-bg">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="row first-row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($tradelist[1]) )
                                        <img class="img-responsive" src="{{ $tradelist[1]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($tradelist[2]) )
                                        <img class="img-responsive" src="{{ $tradelist[2]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row second-row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($tradelist[3]) )
                                        <img class="img-responsive" src="{{ $tradelist[3]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-right-padding small-card">
                                    @if( isset($tradelist[4]) )
                                        <img class="img-responsive" src="{{ $tradelist[4]->deck->getFrontImg('350x250') }}">
                                    @else
                                        <div class="grey-bg">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row item-description-wrapper no-margin">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 item-description">
                            <h3>MY TRADELIST</h3>
                            <span class="decks-amount">{{ $tradelist_items_amount }} DECKS</span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 item-button">
                            <button class="btn btn-red">See more</button>
                        </div>
                    </div>
                    <div class="user-collections-item-bg">
                    </div>
                </div>
            </a>
            {{-- END TRADELIST --}}


            <a href="#" class="user-collections-item-wrapper col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="user-collections-item">
                <div class="lock">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </div>
                <div class="lock-description-wrapper">
                    <div class="lock-description">
                        <span class="earn-more">EARN MORE TO</span>
                        <span class="unlock">UNLOCK</span>
                    </div>
                </div>
                <div class="user-collections-item-bg">
                </div>
            </div>
            </a>
        </div>
    <div>
@endsection
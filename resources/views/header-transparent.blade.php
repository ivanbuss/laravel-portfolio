<header class="header-transparent container">
    <nav class="navbar">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    {{--<span class="icon-bar"></span>--}}
                    {{--<span class="icon-bar"></span>--}}
                    {{--<span class="icon-bar"></span>--}}
                    <span>MENU</span>
                </button>
                <a class="navbar-brand" href="/"><img src="/img/logo.png"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/search">DISCOVER</a>
                    {{--<li><a href="#">MARKET</a></li>--}}
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">LEARN</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/blog') }}">BLOG</a></li>
                            <li><a href="{{ action('LaunchCalendarController@getCalendar') }}">LAUNCH CALENDAR</a></li>
                            <li><a href="{{ url('/how-it-works') }}">HOW IT WORKS</a></li>
                        </ul>
                    </li>
                    <li><a href="#">ABOUT</a></li>
                    <li>
                        <div class="">
                            {!! Form::open(['action'=>['SearchController@searchPost'], 'id'=>'header-search', 'class'=>'']) !!}
                            {!! Form::text('search_string', null, ['class' => 'form-control header-search-input', 'placeholder' => '']) !!}
                            {!! Form::close() !!}
                        </div>
                    </li>
                    <li>
                        @if(Auth::user())
                            <a href="{{ url('/profile/' . Auth::user()->id) }}">
                                <img src="{{ Auth::user()->profile->getAvatarImg('300x300') }}" alt="" class="header-avatar img-circle"/>
                            </a>
                        @endif
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{--@if(Auth::user())--}}
                            {{--<img src="{{ Auth::user()->profile->getAvatarImg('300x300') }}" alt="" class="header-avatar img-circle"/>--}}
                            {{--@endif--}}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @if (Auth::user())
                                <li><a href="{{ url('/profile') }}">{{ Auth::user()->name }}</a></li>
                                <li><a href="{{ action('ProfileController@getEdit', ['id'=>Auth::user()->id]) }}">Edit profile</a></li>
                                <li><a href="{{ url('/logout') }}">Log Out</a></li>
                            @else
                                <li><a href="#" data-toggle="modal" data-target="#signUpModal">Sign Up</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#signUpModal">Log In</a></li>
                            @endif
                                @if (Auth::user() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('contributor')))
                                <li><a href="{{ action('DeckEditController@getAdd') }}">Add new deck</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>
<!-->
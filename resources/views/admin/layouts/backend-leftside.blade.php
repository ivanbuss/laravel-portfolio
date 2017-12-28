<nav class="ts-sidebar">
    <ul class="ts-sidebar-menu">
        <li class="ts-label">Main</li>
        <li{{ ((isset($page) && $page == 'dashboard') ? ' class=open' : '') }}><a href="{{ action('Admin\DashboardController@getDashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li{{ ((isset($page) && $page == 'users_list') ? ' class=open' : '') }}><a href="{{ action('Admin\UsersController@getUsersList') }}"><i class="fa fa-users"></i> Users</a></li>
        <li{{ ((isset($page) && $page == 'uploaded_decks') ? ' class=open' : '') }}><a href="{{ action('Admin\DeckUploadController@getDeckReport') }}"><i class="fa fa-dashboard"></i> Uploaded Decks</a></li>
        <li{{ ((isset($page) && $page == 'rated_decks') ? ' class=open' : '') }}><a href="{{ action('Admin\DeckRatedController@getDecksReport') }}"><i class="fa fa-dashboard"></i> Rated Decks</a></li>
        <li{{ ((isset($page) && $page == 'tagged_decks') ? ' class=open' : '') }}><a href="{{ action('Admin\DeckTaggedController@getDeckReport') }}"><i class="fa fa-dashboard"></i> Tagged Decks</a></li>
        <li{{ ((isset($page) && $page == 'pages') ? ' class=open' : '') }}><a href="{{ action('Admin\PagesController@getListPages') }}"><i class="fa fa-copy"></i> Pages</a></li>

        <!-- Account from above -->
        <ul class="ts-profile-nav">
            <li><a href="#">Help</a></li>
            <li><a href="#">Settings</a></li>
            <li class="ts-account">
                <a href="#"><img src="img/ts-avatar.jpg" class="ts-avatar hidden-side" alt=""> Account <i class="fa fa-angle-down hidden-side"></i></a>
                <ul>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Edit Account</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </li>
        </ul>

    </ul>
</nav>
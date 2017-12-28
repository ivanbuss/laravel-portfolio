<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'welocme', 'uses' => 'HomeController@welcome']);

Route::auth();

// Deck controllers
Route::group(['middleware' => ['contributor']], function() {
    Route::get('/deck/add', 'DeckEditController@getAdd');
    Route::post('/deck/add', 'DeckEditController@postCreate');

    Route::get('/deck/{deck}/edit', 'DeckEditController@getEdit');
    Route::post('/deck/{deck}/edit', 'DeckEditController@postUpdate');

    Route::post('/deck/files/upload/{field}', 'DeckEditController@postFileUpload');
});

Route::get('/deck/{deck}/view', 'DeckViewController@getView');

Route::post('/deck/{deck}/notes', 'DeckEditController@postNotes');

Route::post('/deck/{deck}/terms/edit', 'DeckEditController@postTermsEdit');
Route::post('/deck/{deck}/terms/reset', 'DeckEditController@postTermsReset');

Route::group(['middleware' => ['contributor']], function() {
    Route::get('/deck/gallery/add', 'DeckGalleryController@getGalleryForm');
    Route::post('/deck/gallery/add', 'DeckGalleryController@postCreate');
});

// Terms and dictionaries controllers
Route::get('/ajax/term/{dictionary_name}/single', 'TermsAutocompleteController@getSingleDeckTags');
Route::get('/ajax/term/{dictionary_name}/multiple', 'TermsAutocompleteController@getMultipleDeckTags');
Route::get('/ajax/autocomplete/{key}', 'AutocompleteController@getSingleSuggestions');

// Profile controllers
Route::get('/profile', ['as' => 'profile.show', 'uses' => 'ProfileController@show']);
Route::get('/profile/{user}', ['as' => 'profile.show', 'uses' => 'ProfileController@show']);
Route::get('/profile/{user}/edit', ['as' => 'profile.edit.get', 'uses' =>  'ProfileController@getEdit']);
Route::post('/profile/{user}/edit', 'ProfileController@postEdit');

//Collection controllers
Route::get('/collection', 'CollectionController@getList');
Route::get('/profile/{user}/collection', 'CollectionController@getUserList');
Route::post('/collection/add', 'CollectionController@postAdd');
Route::post('/collection/remove', 'CollectionController@postRemove');
Route::post('/profile/{user}/collection/search', 'CollectionController@postSearch');
Route::post('/collection/reorder', 'CollectionController@postReorder');

//Wishlist controllers
Route::get('/wishlist', 'WishlistController@getList');
Route::get('/profile/{user}/wishlist', 'WishlistController@getUserList');
Route::post('/wishlist/add', 'WishlistController@postAdd');
Route::post('/wishlist/remove', 'WishlistController@postRemove');
Route::post('/profile/{user}/wishlist/search', 'WishlistController@postSearch');
Route::post('/wishlist/reorder', 'WishlistController@postReorder');

//Tradelist controllers
Route::get('/tradelist', 'TradelistController@getList');
Route::get('/profile/{user}/tradelist', 'TradelistController@getUserList');
Route::post('/tradelist/add', 'TradelistController@postAdd');
Route::post('/tradelist/remove', 'TradelistController@postRemove');
Route::post('/profile/{user}/tradelist/search', 'TradelistController@postSearch');
Route::post('/tradelist/reorder', 'TradelistController@postReorder');

// Rating controllers
Route::post('/rate', 'RatingController@rate');

Route::group(['middleware' => ['admin']], function() {
    Route::get('/admin', 'Admin\DashboardController@getDashboard');

    Route::get('/admin/users', 'Admin\UsersController@getUsersList');
    Route::get('/admin/users/data', 'Admin\UsersController@getUsersListTableData');

    Route::get('/admin/report/decks-uploaded', 'Admin\DeckUploadController@getDeckReport');
    Route::get('/admin/report/decks-uploaded/data', 'Admin\DeckUploadController@getDeckReportTableData');
    Route::get('/admin/report/user/{user}/decks-uploaded', 'Admin\DeckUploadController@getUserReport');
    Route::get('/admin/report/user/{user}/decks-uploaded/data', 'Admin\DeckUploadController@getUserDecksTableData');


    Route::get('/admin/report/decks-rated', 'Admin\DeckRatedController@getDecksReport');
    Route::get('/admin/report/decks-rated/data', 'Admin\DeckRatedController@getDeckReportTableData');
    Route::get('/admin/report/deck/{deck}/deck-rated', 'Admin\DeckRatedController@getDeckReport');
    Route::post('/api/deck/{deck}/deck-rated', 'Admin\DeckRatedController@postDeckReport');

    Route::get('/admin/report/decks-tagged', 'Admin\DeckTaggedController@getDeckReport');
    Route::get('/admin/report/decks-tagged/data', 'Admin\DeckTaggedController@getDeckReportTableData');

    Route::get('/admin/pages', 'Admin\PagesController@getListPages');
    Route::get('/admin/pages/data', 'Admin\PagesController@getListPagesTableData');

    Route::get('/admin/pages/create', 'Admin\PagesController@getCreatePage');
    Route::post('/admin/pages/create', 'Admin\PagesController@postCreate');

    Route::get('/admin/pages/{page}/edit', 'Admin\PagesController@getEditPage');
    Route::post('/admin/pages/{page}/edit', 'Admin\PagesController@postUpdate');
    Route::post('/admin/pages/{page}/delete', 'Admin\PagesController@postDelete');

    Route::get('/admin/report/decks-rated-test', 'Admin\DeckRatedController@getTest');
});

// Search controllers
Route::get('/discover', 'SearchController@discoverView');
Route::get('/search', 'SearchController@searchView');
Route::post('/search/post', 'SearchController@searchPost');
Route::post('/search/post/ajax', 'SearchController@searchPostAjax');

// Social login controllers
Route::get('/login/{provider?}', ['uses' => 'Auth\AuthController@getSocialAuth', 'as'   => 'auth.getSocialAuth']);
Route::get('/login/callback/{provider?}', ['uses' => 'Auth\AuthController@getSocialAuthCallback', 'as'   => 'auth.getSocialAuthCallback']);

// Migration
Route::group(['middleware' => ['admin']], function() {
    Route::get('migrate/test', 'P52\MigrateController@test');
    Route::get('migrate', 'P52\MigrateController@migrate');
    Route::post('migrate/post', 'P52\MigrateController@migratePost');
});

//Newsletter controllers
Route::post('newsletter/save', 'NewsletterController@postSave');

Route::get('calendar', 'LaunchCalendarController@getCalendar');
Route::get('calendar/{deck}/view', 'LaunchCalendarController@getDeckView');

Route::get('calendar/{deck}/review/add', 'LaunchCalendarController@getReviewAdd');
Route::post('calendar/{deck}/review/add', 'LaunchCalendarController@postReviewAdd');

Route::get('{page}', 'StaticController@getPage');

// Admin search

Route::get('/elastic/test/', ['uses' => 'Search\ElasticAdminController@elasticTest', 'as'   => 'elastic.test']);

